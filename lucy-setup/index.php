<?php

	// Verifying that the file exists.  If not it will redirect the user to the configuration page.
	// NOTE: SEE THE DOCUMENTATION FOR PERMISSIONS RELATING TO THIS CONFIGUATION FILE.
	if(file_exists(dirname(__FILE__) . '/../lucy-config/config.json')){
		header("Location: ../index.php");
	}


	error_reporting(E_ALL);
	$error = null;

	if(isset($_POST['submit'])){

		// Gathering all of the User information
		$user_name = $_POST['user_name'];
		$user_email = $_POST['user_email'];
		$user_password = $_POST['user_password'];
		
		// Gathering all of the Database information
		$db_type = $_POST['db_type'];
		$db_location = $_POST['db_location'];
		$db_name = $_POST['db_name'];
		$db_username = $_POST['db_username'];
		$db_password = $_POST['db_password'];

		if($db_type != "SQLITE"){
			// Testing for missing information.
			if($user_name == null || $user_email == null || $user_password == null){
				$error = "Please include all of your information...";
				goto docWrite;
			}

			// Testing for missing information
			if($db_type == null || $db_location == null || $db_name == null || $db_username == null || $db_password == null){
				$error = "Please include all of the database information...";
				goto docWrite;
			}
		} else {
			if($db_name == null){
				$error = "Please include all of the database information...";
				goto docWrite;
			}
		}

		// Creating the global variables.
		$GLOBALS['config']['Database']['Type'] = $db_type;
		$GLOBALS['config']['Database']['Location'] = $db_location;
		$GLOBALS['config']['Database']['Name'] = $db_name;
		$GLOBALS['config']['Database']['Username'] = $db_username;
		$GLOBALS['config']['Database']['Password'] = $db_password;

		// Other required settings.
		$GLOBALS['config']['Theme'] = "default";
		$GLOBALS['config']['SessionExpire'] = 5300;
		$GLOBALS['config']['Debug'] = false;
		$GLOBALS['config']['Images']['Enable'] = True;

		// Strings.
		$GLOBALS['config']['Strings']['Main'] = $_POST['strings_title'];
		$GLOBALS['config']['Strings']['Separator'] = $_POST['strings_titlesep'];
		$GLOBALS['config']['Strings']['Footer'] = $_POST['strings_footer'];
		$GLOBALS['config']['Support']['ID'] = "%#%#%#";
		$GLOBALS['config']['Strings']['Home']['Title'] = $_POST['strings_welcome_title'];
		$GLOBALS['config']['Strings']['Home']['Slogan'] = $_POST['strings_welcome_slogan'];

		// Email
		$GLOBALS['config']['Email']['Name'] = $_POST['email_name'];
		$GLOBALS['config']['Email']['Address'] = $_POST['email_email'];
		$GLOBALS['config']['Email']['Footer'] = $_POST['email_footer'];

		// Creating the default value for the input order setting
		$GLOBALS['config']['Support']['Order'] = array('name','email','password','message','image');

		// Enabled Read-Only Mode until forms are designed
		$GLOBALS['config']['ReadOnly'] = true;

		// Creating the configuration file.
		$json = '{"config":' . json_encode($GLOBALS['config']) . '}';

		// Writing the required initial configuration settings.
		if(!file_exists("../lucy-config/config.json")){
			file_put_contents("../lucy-config/config.json", $json);
		} else {
			unlink("../lucy-config/config.json");
			file_put_contents("../lucy-config/config.json", $json);
		}

		$designer = array();
		$designer['static']['name']['title'] = "What is your Name?";
		$designer['static']['name']['helptext'] = "A real name helps keep our emails out of your spam folder";
		$designer['static']['email']['title'] = "What is your Email Address?";
		$designer['static']['email']['helptext'] = "Will be verified";
		$designer['static']['password']['title'] = "Choose a password:";
		$designer['static']['password']['helptext'] = "Keep it a secret!";
		$designer['static']['message']['title'] = "Enter a message";
		$designer['static']['message']['helptext'] = "Enter your message here, make it as detailed as possible!";
		$designer['static']['image']['title'] = "Include a Picture?";
		$designer['static']['image']['helptext'] = "A picture can help us answer your question faster";

		// Creating the designer file.
		$designer = '{"config":{},"static":' . json_encode($designer['static']) . '}';

		// Writing the required initial configuration settings.
		if(!file_exists("../lucy-config/designer.json")){
			file_put_contents("../lucy-config/designer.json", $designer);
		} else {
			unlink("../lucy-config/designer.json");
			file_put_contents("../lucy-config/designer.json", $designer);
		}

		// Including the required libraries to create the sql tables.  If there are any problems these libraries will throw their own errors.
		include("../lucy-admin/defines.php");
		include("../lucy-admin/cda.php");

		// Creating the CDA class.
		$cda = new cda;

		// Initializing the CDA class.
		$cda->init($GLOBALS['config']['Database']['Type']);

		if($db_type != "SQLITE"){
			// Testing database connection settings
			if($cda->testConnection($db_location, $db_username, $db_password) === false){
				$error = "Database connection failed.";
				goto docWrite;
			}
		}

		// The SQL statements for creating the tables.

		// Columns for table: threads
		$threads_cols = array(
			array(
				"name"=>"id",
				"type"=>"varchar",
				"length"=>11,
				"null"=>false
			),
			array(
				"name"=>"owner",
				"type"=>"smallint",
				"length"=>6,
				"null"=>false
			),
			array(
				"name"=>"status",
				"type"=>"varchar",
				"length"=>10,
				"null"=>false
			),
			array(
				"name"=>"subject",
				"type"=>"varchar",
				"length"=>100,
				"null"=>false
			),
			array(
				"name"=>"date",
				"type"=>"datetime",
				"length"=>null,
				"null"=>false
			),
			array(
				"name"=>"lastreply",
				"type"=>"smallint",
				"length"=>6,
				"null"=>false
			),
			array(
				"name"=>"assignedto",
				"type"=>"int",
				"length"=>11,
				"null"=>false
			),
			array(
				"name"=>"data",
				"type"=>"text",
				"length"=>null,
				"null"=>false
			)
		);
		try{
			$cda->createTable("threads",$threads_cols,"id",null);
		} catch (Exception $e) {
			$error = 'Could not create threads Table: ' . $e;
			goto docWrite;
		}

		// Columns for table: userlist
		$userlist_cols = array(
			array(
				"name"=>"id",
				"type"=>"int",
				"length"=>11,
				"null"=>false,
				"ai"=>true
			),
			array(
				"name"=>"name",
				"type"=>"varchar",
				"length"=>45,
				"null"=>false
			),
			array(
				"name"=>"email",
				"type"=>"varchar",
				"length"=>45,
				"null"=>false
			),
			array(
				"name"=>"password",
				"type"=>"varchar",
				"length"=>32,
				"null"=>false
			),
			array(
				"name"=>"salt",
				"type"=>"tinyint",
				"length"=>2,
				"null"=>false
			),
			array(
				"name"=>"verified",
				"type"=>"tinyint",
				"length"=>1,
				"null"=>false
			),
			array(
				"name"=>"tf_secret",
				"type"=>"varchar",
				"length"=>32,
				"null"=>false
			),
			array(
				"name"=>"tf_enable",
				"type"=>"varchar",
				"length"=>32,
				"null"=>false
			),
			array(
				"name"=>"type",
				"type"=>"varchar",
				"length"=>10,
				"null"=>false
			),
			array(
				"name"=>"date_registered",
				"type"=>"date",
				"length"=>null,
				"null"=>false
			)
		);
		try{
			$cda->createTable("userlist",$userlist_cols,"id",array("email"));
		} catch (Exception $e) {
			$error = 'Could not create userlist Table: ' . $e;
			goto docWrite;
		}

		// Columns for table: pwd_reset
		$pwd_cols = array(
			array(
				"name"=>"email",
				"type"=>"varchar",
				"length"=>45,
				"null"=>false
			),
			array(
				"name"=>"salt1",
				"type"=>"varchar",
				"length"=>32,
				"null"=>false
			),
			array(
				"name"=>"salt2",
				"type"=>"varchar",
				"length"=>32,
				"null"=>false
			),
			array(
				"name"=>"date_registered",
				"type"=>"datetime",
				"length"=>null,
				"null"=>false
			),
			array(
				"name"=>"status",
				"type"=>"varchar",
				"length"=>10,
				"null"=>false
			)
		);
		try{
			$cda->createTable("pwd_reset",$pwd_cols,"email",null);
		} catch (Exception $e) {
			$error = 'Could not create passwordreset Table: ' . $e;
			goto docWrite;
		}

		// Setting up the admin userlist
		// Generating a random salt used for encryption.
		$salt = mt_rand(10, 99);

		// Encrypting the password.
		$hashed_password = md5($salt . md5($user_password));
		try{
			$response = $cda->insert("userlist",array("name","email","password","salt","type","date_registered"),array($user_name, $user_email, $hashed_password, $salt,'Admin', date("Y-m-d")));
		} catch (Exception $e) {
			$error = 'Could not create the admin user: ' . $e;
			goto docWrite;
		}

		session_start();
		$_SESSION['id'] = $response['id'];
		$_SESSION['name'] = $user_name;
		$_SESSION['type'] = 'Admin';
		$_SESSION['email'] = $user_email;
		$_SESSION['LAST_ACTIVITY'] = time();
		
		header("Location: ../lucy-admin/ui/");
		die();
	}

	session_start();
	session_unset();
	session_destroy();

	$disableForm = false;

	$files = array(
		'lucy-admin/auth.php',
		'lucy-admin/cda.php',
		'lucy-admin/defines.php',
		'lucy-admin/index.php',
		'lucy-admin/mailer.php',
		'lucy-admin/mysql.php',
		'lucy-admin/session.php',
		'lucy-admin/sqlite.php',
		'lucy-admin/api/admin_auth_setup.php',
		'lucy-admin/api/admin_del_message.php',
		'lucy-admin/api/admin_designer_add.php',
		'lucy-admin/api/admin_designer_change_order.php',
		'lucy-admin/api/admin_designer_edit_constant.php',
		'lucy-admin/api/admin_designer_edit.php',
		'lucy-admin/api/admin_designer_remove.php',
		'lucy-admin/api/admin_edit_message.php',
		'lucy-admin/api/admin_edit_thread.php',
		'lucy-admin/api/admin_flag_spam.php',
		'lucy-admin/api/admin_get_users.php',
		'lucy-admin/api/thread_close.php',
		'lucy-admin/api/thread_reopen.php',
		'lucy-admin/api/thread_update.php',
		'lucy-admin/ui/allthreads.php',
		'lucy-admin/ui/auth.php',
		'lucy-admin/ui/default.php',
		'lucy-admin/ui/del_thread.php',
		'lucy-admin/ui/del_user.php',
		'lucy-admin/ui/designer.php',
		'lucy-admin/ui/designer_preview.php',
		'lucy-admin/ui/edit_user.php',
		'lucy-admin/ui/index.php',
		'lucy-admin/ui/mythreads.php',
		'lucy-admin/ui/new_user.php',
		'lucy-admin/ui/purge.php',
		'lucy-admin/ui/readonly.php',
		'lucy-admin/ui/settings.php',
		'lucy-admin/ui/users.php',
		'lucy-admin/ui/view_thread.php'
	);

	foreach ($files as $file) {
		if(!file_exists('../' . $file)){
			echo('<span class="label label-danger">Error</span> The <code>' . $file . '</code> is missing or corrupt.  This may cause problems when running this setup script<br>');
			$disableForm = true;
		}
	}

	if(!is_writable('../lucy-config')){
		echo('<span class="label label-danger">Error</span> Lucy does not have write permissions to the <code>lucy-config</code> directory and will not be able to save the settings.  This may cause problems when running this setup script');
		$disableForm = true;
	}

	docWrite:
	if(!empty($error)){ @unlink(realpath("../lucy-config/config.json")); }
	if(!empty($error)){ @unlink(realpath("../lucy-config/designer.json")); }
?>
<!DOCTYPE html>
<html lang="en-US">
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title>Lucy Setup</title>
<div class="container">
<?php if($error) { ?><div class="alert alert-danger"><?php echo($error); ?></div><?php } ?>
<?php if(!$disableForm){ ?>
<h1>Welcome to Lucy! <small>Let's get a few things sorted before we begin</small></h1>
<form method="post" class="form-horizontal" autocomplete="off">
<h2>The Basics <small>Required</small></h2>
<div class="form-group">	
	<label class="col-sm-4 control-label">Your Name</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="user_name" placeholder="Rob Frog" required/>
	</div>
</div>
<div class="form-group">	
	<label class="col-sm-4 control-label">Your Email</label>
	<div class="col-sm-7">
		<input class="form-control" type="email" name="user_email" placeholder="rob@frog.com" required/>
	</div>
</div>
<div class="form-group">	
	<label class="col-sm-4 control-label">Your Password</label>
	<div class="col-sm-7">
		<input class="form-control" type="password" name="user_password" required/>
	</div>
</div>
<h2>Database Settings <small>Required</small></h2>
<div class="form-group">
	<label class="col-sm-4 control-label">Database Type:</label>
	<div class="col-sm-7">
		<select class="form-control" name="db_type" id="db_type">
			<option value="MYSQL">MySQL</option>
			<option value="MYSQLI" disabled>MySQLi</option>
			<option value="MSSQL" disabled>Microsoft SQL Server</option>
			<option value="SQLITE">SQLite</option>
		</select>
	</div>
</div>
<div class="form-group" id="control-group-location">
	<label class="col-sm-4 control-label">Database Location:</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="db_location" title="The location or URL of your database." value="localhost"/>
	</div>
</div>
<div class="form-group" id="control-group-name">
	<label class="col-sm-4 control-label">Database Name:</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="db_name" title="The name of the database that Lucy will use." value="lucy"/>
	</div>
</div>
<div class="form-group" id="control-group-username">
	<label class="col-sm-4 control-label">Database Username:</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="db_username" title="The username for the database connection." value="lucy"/>
	</div>
</div>
<div class="form-group" id="control-group-password">
	<label class="col-sm-4 control-label">Database Password:</label>
	<div class="col-sm-7">
		<input class="form-control" type="password" name="db_password" title="The password for the database connection."/>
	</div>
</div>
<h2>Appearance Settings <small>Optional</small></h2>
<div class="form-group">
	<label class="col-sm-4 control-label">Main Title:</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="strings_title" value="Lucy"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Title Separator:</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="strings_titlesep" value=" — "/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Footer Text:</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="strings_footer" value="Copyright &copy; 2014"/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Welcome Page Title:</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="strings_welcome_title" value="You've got questions, we have the answers."/>
	</div>
</div>
<div class="form-group">
	<label class="col-sm-4 control-label">Welcome Page Slogan:</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="strings_welcome_slogan" value="Sign up and start asking today"/>
	</div>
</div>
<h2>Email Settings <small>Optional</small></h2>
<div class="form-group">	
	<label class="col-sm-4 control-label">Email Sender Name</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="email_name" value="Lucy" />
	</div>
</div>
<div class="form-group">	
	<label class="col-sm-4 control-label">Email Sender Address</label>
	<div class="col-sm-7">
		<input class="form-control" type="email" name="email_email" value="lucy@<?php echo($_SERVER['SERVER_NAME']); ?>" />
	</div>
</div>
<div class="form-group">	
	<label class="col-sm-4 control-label">Email Message Footer</label>
	<div class="col-sm-7">
		<input class="form-control" type="text" name="email_footer" value="With Love, The Lucy Team" />
	</div>
</div>
<div class="form-group">	
	<label class="col-sm-4 control-label"></label>
	<div class="col-sm-7">
		<input type="submit" name="submit" class="btn btn-success" value="Save Settings!" />
	</div>
</div>
</form>
<?php } ?>
</div>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
$('#db_type').change(function() {
  if($("#db_type").val() == "SQLITE"){
		$('#control-group-location').hide();
		$('#control-group-username').hide();
		$('#control-group-password').hide();	
	} else {
		$('#control-group-location').show();
		$('#control-group-username').show();
		$('#control-group-password').show();	
	}
});
</script>