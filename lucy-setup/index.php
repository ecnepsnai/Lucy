<?php
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
		$db_type = "MYSQL";
		$db_location = $_POST['db_location'];
		$db_name = $_POST['db_name'];
		$db_username = $_POST['db_username'];
		$db_password = $_POST['db_password'];


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
		include("../lucy-admin/sql.php");

		// The SQL statements for creating the tables.  When other database types are supports these will not be hard-coded.
		// These statements were generated using PHPMyAdmin.
		$sql[0] = "CREATE TABLE IF NOT EXISTS `ticketlist` (`id` varchar(11) NOT NULL, `name` varchar(45) NOT NULL, `email` varchar(45) NOT NULL, `application` varchar(45) NOT NULL, `version` varchar(45) NOT NULL, `os` varchar(45) NOT NULL, `status` enum('Pending','Active','Closed') NOT NULL, `subject` varchar(100) NOT NULL, `date` datetime NOT NULL, `lastreply` enum('Client','Agent','Bot') NOT NULL, `assignedto` int(11) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[1] = "CREATE TABLE IF NOT EXISTS `userlist` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(45) NOT NULL, `email` varchar(45) NOT NULL, `password` varchar(32) NOT NULL, `salt` tinyint(2) NOT NULL, `tf_enable` tinyint(1) NOT NULL, `tf_secret` varchar(32) NOT NULL, `type` enum('Admin','Agent','Client','Bot') NOT NULL, `date_registered` date NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `email` (`email`) ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;";
		$sql[2] = "CREATE TABLE IF NOT EXISTS `pwd_reset` (`email` varchar(45) NOT NULL, `salt1` varchar(32) NOT NULL, `salt2` varchar(32) NOT NULL, `date` datetime NOT NULL, `status` enum('Requested','Reset') NOT NULL, PRIMARY KEY (`email`) ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
		$sql[3] = "INSERT INTO `userlist` (`id`, `name`, `email`, `password`, `salt`, `tf_enable`, `type`, `date_registered`) VALUES (1, 'Spam Bot', '', '', 0, 0, 'Bot', '0000-00-00');";
		

		// Setting up the admin userlist
		// Generating a random salt used for encryption.
		$salt = mt_rand(10, 99);

		// Encrypting the password.
		$hashed_password = md5($salt . md5($user_password));

		// Creating the statement to inset the admin user.
		$sql[4] = "INSERT INTO  `userlist` (`type`, `name`, `email`, `password`, `date_registered`, `salt`) VALUES ('Admin',  '" . $user_name . "',  '" . $user_email . "',  '" . $hashed_password . "',  '" . date("Y-m-d") . "', '". $salt ."');";
		for ($i=0; $i < 5; $i++) { 
			try {
				sqlQuery($sql[$i], True);
			} catch (Exception $e) {
				die($e);
			}
		}
		

		// If there were no errors the user will be redirected to the setting page to finish the setup.
		header("Location: ../login.php?notice=welcome");
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
			color:red;
		}
		.container{
			width: 500px;
			margin: 0 auto;
			color:#000;
		}
		input{
			border: 1px solid #8fa0ae;
			padding: 2px;
			-webkit-transition: border linear .2s,box-shadow linear .2s;
			-moz-transition: border linear .2s,box-shadow linear .2s;
			-o-transition: border linear .2s,box-shadow linear .2s;
			transition: border linear .2s,box-shadow linear .2s;
		}
		input:focus{
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
		}
	</style>
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
					<td><input type="email" name="user_email" maxlength="255" /></td>
				</tr>
				<tr>
					<td class="first"><strong>Chose a Password:</strong></td>
					<td><input type="password" name="user_password" maxlength="255" /></td>
				</tr>
			</table>
			<h2>Database Settings</h2>
			<table>
				<tr>
					<td class="first"><strong>Database Type:</strong></td>
					<td>
						<select name="db_type" disabled="disabled">
							<option value="MYSQL">MySQL</option>
							<option value="MYSQLI" disabled="disabled">MySQLi</option>
							<option value="MSSQL" disabled="disabled">Microsoft SQL Server</option>
							<option value="SQLITE" disabled="disabled">SQLite</option>
						</select><br/>
						<em>*Currently only MySQL is supported.</em>
					</td>
				</tr>
				<tr>
					<td class="first"><strong>Database Location:</strong></td>
					<td>
						<input type="text" name="db_location"/>
					</td>
				</tr>
				<tr>
					<td class="first"><strong>Database Name:</strong></td>
					<td>
						<input type="text" name="db_name"/>
					</td>
				</tr>
				<tr>
					<td class="first"><strong>Database Username:</strong></td>
					<td>
						<input type="text" name="db_username"/>
					</td>
				</tr>
				<tr>
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