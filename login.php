<?php
require("assets/lib/session.php");
require("assets/lib/sql.php");
$login_error = False;

// Obviously if the user is already signed in, we don't let them log in again.
if($usr_IsSignedIn){
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
}

// User chose to login.
if(isset($_POST['submit'])){
	// Getting the raw inputs.
	$raw_email = trim($_POST['email']);
	if(empty($raw_email) || empty($_POST['pwd'])){
		require("error_empty.php");
	}

	// Preparing the first sql request.
	$inp_email = addslashes($raw_email);
	// Ask for user salt first, then verify, THEN get rest of data
	$sql = "SELECT email, salt FROM userlist WHERE email = '". $inp_email . "'";
	try {
		$pw = sqlQuery($sql, True);
	} catch (Exception $e) {
		require("error_db.php");
	}
	$pw_hash = md5($pw['salt'] . md5(trim($_POST['pwd'])));

	// Preparing the second sql request.
	$sql = "SELECT id, name, type, email FROM userlist WHERE email = '". $inp_email ."' AND password = '" . $pw_hash . "'";
	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		require("error_db.php");
	}
	if(empty($user['id'])){
		$login_error = True;
	} elseif($user['type'] == "Ban"){
		?>
			<?php documentCreate(TITLE_ERROR, False); ?>
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

		// Creating the session data for the user.
		session_start();
		$_SESSION['id'] = $user['id'];
		$_SESSION['name'] = $user['name'];
		$_SESSION['type'] = $user['type'];
		$_SESSION['email'] = $user['email'];
		$_SESSION['LAST_ACTIVITY'] = time();

		// If there was a redirect parameter set, navigate to that url.  Will only work for local urls.
		if($_GET['rdirect']){
			die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . $_GET['rdirect'] . "\">Redirecting...");
		}

		// Moves the user to the dash.
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
	}
}
?>
<?php documentCreate(TITLE_LOGIN, False); ?>
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
<a href="signup.php">Need an account?</a> | <a href="forgot.php">Forgot your password?</a>
<div id="SPACE">
	<!-- Space space wanna go to space yes please space. Space space. Go to space. -->
</div>
</div>
<?php writeFooter(); ?>
</div>