<?php
require("session.php");
$login_error = False;
if($usr_IsSignedIn){
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
}
if(isset($_POST['submit'])){
	$raw_email = trim($_POST['email']);
	if(empty($raw_email) || empty($_POST['pwd'])){
		require("error_empty.php");
	}
	require("db_connect.php");
	$inp_email = mysql_real_escape_string($raw_email);
    // Ask for user salt first, then verify, THEN get rest of data
    $pw_sql = "SELECT email, salt FROM userlist WHERE email = '". $inp_email . "'";
    $pw_req = mysql_query($pw_sql);
    if(!$pw_req) {
        require_once("error_db.php");
    }
    $pw_arr = mysql_fetch_array($pw_req);
    $pw_hash = md5($pw_arr['salt'] . md5(trim($_POST['pwd'])));
    $sql = "SELECT id, name, type, email FROM userlist WHERE email = '". $inp_email ."' AND password = '" . $pw_hash . "'";
    $request = mysql_query($sql);
	if(!$request){
		require_once("error_db.php");
	}
	$row = mysql_fetch_array($request);
	if(empty($row['id'])){
		$login_error = True;
	} elseif($row['type'] == "Ban"){
		?>
			<?php documentCreate(TITLE_ERROR, False, False, null, null); ?>
			<div id="wrapper">
			<?php writeHeader(); ?>
			<div id="content">
			<div class="notice" id="red">
				<strong>Account Banned</strong><br/>
				Your account has been banned.
			</div>
			</div>
			<?php writeFooter(); ?>
			</div>
		<?php
		die();
	} else {
		session_start();
		echo session_id();
		$_SESSION['id'] = $row['id'];
		$_SESSION['name'] = $row['name'];
		$_SESSION['type'] = $row['type'];
		$_SESSION['email'] = $row['email'];
		$_SESSION['LAST_ACTIVITY'] = time();
		if($_GET['rdirect']){
			die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . $_GET['rdirect'] . "\">Redirecting...");
		}
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
	}
}
?>
<?php documentCreate(TITLE_LOGIN, False, False, null, null); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<?php
		if($_GET['notice'] == "login"){
			?>
			<div class="notice" id="yellow">
				<strong>You need to be signed in to do that.</strong><br/>
				Don't have an account? <a href="signup.php">Create one here</a>.
			</div>
			<?php
		}
	?>
<h2>Login</h2>
<?php if($login_error) {
	?>
<div class="notice" id="red">
	<strong>Incorrect Password</strong><br/>
	Please try again...
</div>
	<?php
}
?>
<script type="text/javascript">
	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function validateForm() {
		var x_email=document.forms["fm_login"]["email"].value;
		var x_password=document.forms["fm_login"]["pwd"].value;

		if(x_email == "" || x_password == "") {
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
<form method="POST" name="fm_login" onSubmit="return validateForm()">
	<p>Email Address:<br/><input type="email" name="email" class="txtglow" size="45"/></p>
	<p>Password:<br/><input type="password" name="pwd" class="txtglow" size="45"/></p>
	<p><input type="submit" name="submit" value="Log in" class="btn" id="blue"/></p>
</form>
</div>
<?php writeFooter(); ?>
</div>