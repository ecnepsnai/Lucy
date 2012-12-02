<?php 
	require_once("db_connect.php"); 
	if($db_IsSetup){
		if($_POST['table'] == "create"){
			$sql = "CREATE TABLE IF NOT EXISTS `ticketlist` (`id` varchar(10) NOT NULL, `name` varchar(45) NOT NULL, `email` varchar(45) NOT NULL, `application` enum('My Application') NOT NULL, `version` varchar(10) NOT NULL, `os` varchar(45) NOT NULL, `status` enum('Open','Closed') NOT NULL, `subject` varchar(100) NOT NULL, `date` datetime NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
			$request = mysql_query($sql);
			if(!$request){
				die("Uh oh.  Lucy wasn't able to create the ticket table!  The reason was: " . mysql_error());
			}
			$sql = "CREATE TABLE IF NOT EXISTS `userlist` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(45) NOT NULL, `email` varchar(45) NOT NULL, `password` varchar(32) NOT NULL, `salt` varchar(2) NOT NULL, `type` enum('Admin','Client','Bot','Ban') NOT NULL, `date_registered` datetime NOT NULL, `rig_specs` text NOT NULL, PRIMARY KEY (`id`), UNIQUE KEY `email` (`email`) ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;"; 
			$request = mysql_query($sql);
			if(!$request){
				die("Uh oh.  Lucy wasn't able to create the user table!  The reason was: " . mysql_error());
			}
			$tables_Created = True;
		}
		if($_POST['user'] == "create"){
			$raw_name = trim($_POST['name']);
			$raw_email = trim($_POST['email']);
			if(empty($raw_name) || empty($raw_email) || empty($_POST['pwd'])){
				require("error_empty.php");
			}
			$salt = mt_rand(10, 99);
			$hashed_password = md5($salt . md5($_POST['pwd']));
			$inp_name = mysql_real_escape_string($raw_name);
			$inp_email = mysql_real_escape_string($raw_email);
			$sql = "INSERT INTO  userlist (type, name, email, password, date_registered, salt)"; 
			$sql.= " VALUES ('Admin',  '" . $inp_name . "',  '" . $inp_email . "',  '";
			$sql.= $hashed_password . "',  '" . date("Y-m-d") . "', '". $salt ."');";
			$request = mysql_query($sql);
			if(!$request){
				die("Uh on.  Lucy wasn't able to create the administrator user!  The reason was: " . mysql_error());
			}
			$user_Created = True;
		}
	}
?>
<!doctype html>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<style>
h1,h2,h3,h4,h5,h6,p,blockquote {
	margin: 0;
	padding: 0;
}
body {
	font-family: "Helvetica Neue", Helvetica, "Hiragino Sans GB", Arial, sans-serif;
	font-size: 13px;
	line-height: 18px;
	color: #737373;
	margin: 10px 13px 10px 13px;
}
a {
	color: #0069d6;
}
a:hover {
	color: #0050a3;
	text-decoration: none;
}
a img {
	border: none;
}
p {
	margin-bottom: 9px;
}
h1,h2,h3,h4,h5,h6 {
	color: #404040;
	line-height: 36px;
}
h1 {
	margin-bottom: 18px;
	font-size: 30px;
}
h2 {
	font-size: 24px;
}
h3 {
	font-size: 18px;
}
h4 {
	font-size: 16px;
}
h5 {
	font-size: 14px;
}
h6 {
	font-size: 13px;
}
hr {
	margin: 0 0 19px;
	border: 0;
	border-bottom: 1px solid #ccc;
}
blockquote {
	padding: 13px 13px 21px 15px;
	margin-bottom: 18px;
	font-family:georgia,serif;
	font-style: italic;
}
blockquote:before {
	content:"\201C";
	font-size:40px;
	margin-left:-10px;
	font-family:georgia,serif;
	color:#eee;
}
blockquote p {
	font-size: 14px;
	font-weight: 300;
	line-height: 18px;
	margin-bottom: 0;
	font-style: italic;
}
code, pre {
	font-family: Monaco, Andale Mono, Courier New, monospace;
}
code {
	background-color: #fee9cc;
	color: rgba(0, 0, 0, 0.75);
	padding: 1px 3px;
	font-size: 12px;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
}
pre {
	display: block;
	padding: 14px;
	margin: 0 0 18px;
	line-height: 16px;
	font-size: 11px;
	border: 1px solid #d9d9d9;
	white-space: pre-wrap;
	word-wrap: break-word;
}
pre code {
	background-color: #fff;
	color:#737373;
	font-size: 11px;
	padding: 0;
}
@media screen and (min-width: 768px) {
	body {
		width: 748px;
		margin:10px auto;
	}
}
</style>
<title>Welcome to Lucy!</title>
<h1>Welcome to Lucy!</h1>
<p>You're just a few keystrokes away from a super easy-to-use support system.  But before we begin, you need to set up a few settings so Lucy can work with your database.</p>

<?php

if($user_Created){
	?>
<h2>You're all set!</h2>
<p>That's all for now!  Make sure to head over to Lucy Settings to finish the setup.</p><button onClick="parent.location='login.php'">Let's get started!</button>
	<?php
die(); }

if(!$db_IsSetup) {
	?>
<h2>Add database information</h2>
<p>Lucy can not function properly without a database, so you will need to create one.  However every hosting does this a bit differently so you will have to contact them if you need assistance doing that!</p>

<h3>Adding database information to Lucy</h3>
<p>Lucy came with a file called db_connect.php, open that file in a Notepad (windows) or TextEdit (OS X) and it will have more information.</p>

<h4>Database Location</h4>
<p>This is the URL where your database is located.  When you create a database with your host, they will provide this url.</p>

<h4>Database Username</h4>
<p>The Username that Lucy will use to access the database.  Lucy will need Select, Insert, Alter, Create, and Drop permissions.  When you create a database with your hose, you may be asked to create a username.</p>

<h4>Database Password</h4>
<p>The password for the above user.  When you create a database with your hose, you may be asked to create a password.</p>

<h4>Database Name</h4>
<p>The name of the database that Lucy will use.  You will have to pick a name when creating a database with your host.</p>

<button onClick="parent.location='lucy-setup.php'">I've edited db_connect.php!</button><br/><em>Note: It may take a second for your server to update the file...</em>
	<?php
} elseif(!$tables_Created) {
	?>  
<h2>Creating the Tables</h2>
<p>Awesome!  Lucy can now talk with you database.  Now we will need to create some tables so that it can function properly.  Luckily, all you have to do is click a button!</p>
<form method="POST">
	<input type="hidden" name="table" value="create"/>
	<input type="submit" name="submit" value="Automagically create tables!" />
</form>
	<?php
} else {
	?>
<h2>Create the Administrator user</h2>
<p>Once the tables have been created, you need to create an administrator user that can access Lucy's settings after the configuration is complete.</p>
<form method="POST">
	<input type="hidden" name="user" value="create"/>
	<p>What's your name:<br/><input type="text" name="name" size="45"/></p>
	<p>And your email address:<br/><input type="email" name="email" size="45"/></p>
	<p>And finally a password:<br/><input type="password" name="pwd" size="45"/></p>
	<p><input type="submit" name="submit" value="Make User"/></p>
</form>
<?php } ?>
</html>
<?php die(); ?>