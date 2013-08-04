<?php
	error_reporting(E_ALL);
	$error = null;

	// Verifying that the file exists.  If not it will redirect the user to the configuration page.
	// NOTE: SEE THE DOCUMENTATION FOR PERMISSIONS RELATING TO THIS CONFIGUATION FILE.
	if(file_exists(dirname(__FILE__) . '\..\lucy-config\config.json')){
		header("Location: ../index.php");
	}

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

			// Testing for missing information (except Database Password)
			if($db_type == null || $db_location == null || $db_name == null || $db_username == null){
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

		// Setting the optional database setting.
		if($db_password == "" || $db_password == null){
			$GLOBALS['config']['Database']['Password'] = null;
			$GLOBALS['config']['Database']['nullpwd'] = True;
		} else {
			$GLOBALS['config']['Database']['Password'] = $db_password;
			$GLOBALS['config']['Database']['nullpwd'] = False;
		}

		// Other required settings.
		$GLOBALS['config']['Theme'] = "default";
		$GLOBALS['config']['SessionExpire'] = 5300;

		// Creating the json.
		$json = '{"config":' . json_encode($GLOBALS['config']) . '}';

		// Writing the required initial configuration settings.
		if(!file_exists("../lucy-config/config.json")){
			file_put_contents("../lucy-config/config.json", "");
			file_put_contents("../lucy-config/config.json", $json);
		} else {
			unlink("../lucy-config/config.json");
			file_put_contents("../lucy-config/config.json", $json);
		}

		// Including the required libraries to create the sql tables.  If there are any problems these libraries will throw their own errors.
		include("../lucy-admin/defines.php");
		include("../lucy-admin/cda.php");

		// Creating the CDA class.
		$cda = new cda;
		// Initializing the CDA class.
		$cda->init($GLOBALS['config']['Database']['Type']);

		// The SQL statements for creating the tables.

		// Columns for table: ticketlist
		$ticketlist_cols = array(
			array(
				"name"=>"id",
				"type"=>"varchar",
				"length"=>11,
				"null"=>false
			),
			array(
				"name"=>"owner",
				"type"=>"int",
				"length"=>11,
				"null"=>false
			),
			array(
				"name"=>"email",
				"type"=>"varchar",
				"length"=>45,
				"null"=>false
			),
			array(
				"name"=>"application",
				"type"=>"varchar",
				"length"=>45,
				"null"=>false
			),
			array(
				"name"=>"version",
				"type"=>"varchar",
				"length"=>45,
				"null"=>false
			),
			array(
				"name"=>"os",
				"type"=>"varchar",
				"length"=>45,
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
				"type"=>"varchar",
				"length"=>10,
				"null"=>false
			),
			array(
				"name"=>"assignedto",
				"type"=>"int",
				"length"=>11,
				"null"=>false
			)
		);
		try{
			$cda->createTable("ticketlist",$ticketlist_cols,"id",null);
		} catch (Exception $e) {
			die('Could not create Ticketlist Table: ' . $e);
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
				"name"=>"tf_enable",
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
			die('Could not create Userlist Table: ' . $e);
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
			die('Could not create PasswordReset Table: ' . $e);
		}
		try{
			$cda->insert("userlist",array("id","name","email","password","salt","type","date_registered"),array(1, 'Spam Bot', '', '', 0, 'Bot', '0000-00-00'));
		} catch (Exception $e) {
			die('Could not create Spam Bot user: ' . $e);
		}

		// Setting up the admin userlist
		// Generating a random salt used for encryption.
		$salt = mt_rand(10, 99);

		// Encrypting the password.
		$hashed_password = md5($salt . md5($user_password));
		try{
			$cda->insert("userlist",array("name","email","password","salt","type","date_registered"),array($user_name, $user_email, $hashed_password, $salt,'Admin', date("Y-m-d")));
		} catch (Exception $e) {
			die('Could not create Admin user: ' . $e);
		}
		

		// If there were no errors the user will be redirected to the setting page to finish the setup.
		die('<a href="../login.php?notice=welcome">Setup Complete - Continue to Login</a>');
	}
	docWrite:
?>
<!doctype html>
<html>
<head>
	<title>Lucy Setup</title>
	<style>
		body{
			font-family: Helvetica, Arial, sans-serif;
			font-weight: bold;
			color:red;
		}
		.container{
			width: 500px;
			margin: 0 auto;
			font-weight: normal;
			color:#000;
		}
		input[type="text"],input[type="password"],select{
			border: 1px solid #8fa0ae;
			padding: 3px;
			border-radius: 3px;
			-webkit-transition: border linear .2s,box-shadow linear .2s;
			-moz-transition: border linear .2s,box-shadow linear .2s;
			-o-transition: border linear .2s,box-shadow linear .2s;
			transition: border linear .2s,box-shadow linear .2s;
		}
		input[type="text"]:focus,input[type="password"]:focus,select:focus{
			border-color: rgba(82,168,236,0.8);
			outline: 0;
			outline: thin dotted \9;
			-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(82,168,236,.6);
			-moz-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(82,168,236,.6);
			box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(82,168,236,.6);
		}
		strong,em{ color: #000; }
		h2{
			border-bottom: 1px solid #ccc;
		}
		td.first{
			text-align: right;
			width: 200px;
		}
	</style>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
</head>
<body>
	<?php if($error) { echo($error); } ?>
	<div class="container">
		<form method="post" autocomplete="off">
			<h1>Welcome to Lucy!</h1>
			You're just steps away from an easy-to-use Support System.
			<h2>The Basics</h2>
			<table>
				<tr>
					<td class="first"><strong>Your Name:</strong></td>
					<td><input type="text" name="user_name" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="first"><strong>Your Email:</strong></td>
					<td><input type="text" name="user_email" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="first"><strong>Chose a Password:</strong></td>
					<td><input type="password" name="user_password" maxlength="255" /></td>
				</tr>
			</table>
			<h2>Database Settings</h2>
			<table>
				<tr id="row_db_type">
					<td class="first"><strong>Database Type:</strong></td>
					<td>
						<select name="db_type" id="db_type">
							<option value="MYSQL">MySQL</option>
							<option value="MYSQLI" disabled="disabled">MySQLi</option>
							<option value="MSSQL" disabled="disabled">Microsoft SQL Server</option>
							<option value="SQLITE">SQLite3</option>
						</select><br/>
						<em>*Currently only MySQL and SQLite3 are supported.</em>
					</td>
				</tr>
				<tr id="db_lrow_ocation">
					<td class="first"><strong>Database Location:</strong></td>
					<td>
						<input type="text" name="db_location"/>
					</td>
				</tr>
				<tr id="row_db_name">
					<td class="first"><strong>Database Name:</strong></td>
					<td>
						<span id="sqlite3x1" style="display:none">lucy-config\</span> <input type="text" name="db_name"/> <span id="sqlite3x2" style="display:none">.sql</span>
					</td>
				</tr>
				<tr id="db_urow_sername">
					<td class="first"><strong>Database Username:</strong></td>
					<td>
						<input type="text" name="db_username"/>
					</td>
				</tr>
				<tr id="db_prow_assword">
					<td class="first"><strong>Database Password:</strong></td>
					<td>
						<input type="password" name="db_password"/> (Optional)
					</td>
				</tr>
			</table>
			<h2>That's all for now!</h2>
			You can change these after we're done. 
			<input type="submit" name="submit" value="Finish Setup" />
		</form>
	</div>
</body>
</html>

<script type="text/javascript">
$('#db_type').change(function() {
  if(document.getElementsByName("db_type")[0].value == "SQLITE"){
		$('#db_lrow_ocation').hide();
		$('#db_urow_sername').hide();
		$('#db_prow_assword').hide();
		$('#sqlite3x1').show();
		$('#sqlite3x2').show();		

	} else {
		$('#db_lrow_ocation').show();
		$('#db_urow_sername').show();
		$('#db_prow_assword').show();
		$('#sqlite3x1').hide();
		$('#sqlite3x2').hide();		

	}
});
</script>