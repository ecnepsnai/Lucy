<?php
require("session.php");
if($usr_IsSignedIn){
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
}
if(isset($_POST['submit'])){
	$raw_name = trim($_POST['name']);
	$raw_email = trim($_POST['email']);
	if(empty($raw_name) || empty($raw_email) || empty($_POST['pwd'])){
		require("error_empty.php");
	}
    $salt = mt_rand(10, 99);
	$hashed_password = md5($salt . md5($_POST['pwd']));
	require("db_connect.php");
	$inp_name = mysql_real_escape_string($raw_name);
	$inp_email = mysql_real_escape_string($raw_email);
	$sql = "INSERT INTO  userlist (type, name, email, password, date_registered, salt)"; 
    $sql.= " VALUES ('Client',  '" . $inp_name . "',  '" . $inp_email . "',  '";
    $sql.= $hashed_password . "',  '" . date("Y-m-d") . "', '". $salt ."');";
    $request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}
	echo("Welcome, " . $inp_name);
}
documentCreate(TITLE_SIGNUP, True); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
<h2>Signup</h2>
<script type="text/javascript">
	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function validateForm() {
		var x_name=document.forms["fm_signup"]["name"].value;
		var x_email=document.forms["fm_signup"]["email"].value;
		var x_password=document.forms["fm_signup"]["pwd"].value;

		if(x_name = "" || x_email == "" || x_password == "") {
			alert("A required field is blank.");
			return false;
		}
		if(!validateEmail(x_email)) {
			alert("The email address you provided was not valid.");
			return false;
		}
		return true;
	}
</script>
<form method="POST" name="fm_signup" onSubmit="return validateForm()">
	<p>Name:<br/><input type="text" name="name" class="txtglow" size="45"/></p>
	<p>Email Address:<br/><input type="email" name="email" class="txtglow" size="45"/></p>
	<p>Password:<br/><input type="password" name="pwd" class="txtglow" size="45"/></p>
	<p><input type="submit" name="submit" value="Log in" class="btn" id="blue"/></p>
</form>
</div>
<?php writeFooter(); ?>
</div>