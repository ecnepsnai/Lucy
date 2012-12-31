<?php
require("assets/lib/session.php");
$reset_error = False;

// Obviously if the user is already signed in, we don't let them log in again.
if($usr_IsSignedIn){
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
}

// User requested a password reset.
if(isset($_POST['submit'])){
	$inp_email = addslashes(trim($_POST['email']));
	require("assets/lib/sql.php");

	// Check to see if that email even exists in the database.
	$sql = "SELECT id, email FROM userlist WHERE email = '". $inp_email ."'";
	echo($sql);
	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		require("error_db.php");
	}
	if(isset($user['id']) && isset($user['email'])) {
		$salt1 = md5(rand(10,99));
		$salt2 = md5(rand(10,99) . $salt1);
		$sql = "INSERT INTO pwd_reset (`email`, `salt1`, `salt2`) VALUES ('" . $user['email'] . "', '" . $salt1 . "', '" . $salt2 . "')";
		echo($sql);
		try {
			sqlQuery($sql);
		} catch (Exception $e) {
			require("error_db.php");
		}
		documentCreate("Password Reset", False); ?>
		<div id="wrapper">
		<?php writeHeader(); ?>
		<div id="content">
			<div class="notice" id="blue">
			<strong>Password Reset</strong><br/>
			Check your email!  It might be in your spam folder, too.
			</div>
		</div>
		<?php writeFooter(); ?>
		</div>
		<?php die();
	}
}

// User clicked the request new password link emailed to them.
if(isset($_GET['a']) && isset($_GET['b'])){
	require("assets/lib/sql.php");
	$salt1 = addslashes($_GET['a']);
	$salt2 = addslashes($_GET['b']);
	$sql = "SELECT email FROM pwd_reset WHERE salt1 = '" . $salt1 . "' AND salt2 = '" . $salt2 . "'";
	try {
		$reset = sqlQuery($sql, True);
	} catch (Exception $e) {
		require("error_db.php");
	}

	// Checking for a returned email address.
	if(isset($reset['email'])) {
		// Generates a new salt and password for the user
		$salt = mt_rand(10, 99);
		$password = mt_rand(10, 99) . chr(97 + mt_rand(0, 25)) . mt_rand(1000, 9999) . chr(97 + mt_rand(0, 25));
		$hashed_password = md5($salt . md5($password));

		// Updates the userlist to have the new password.
		$sql = "UPDATE userlist SET password = '" . $hashed_password . "', salt = '" . $salt . "' WHERE email = '" . $reset['email'] . "';";
		try {
			sqlQuery($sql);
		} catch (Exception $e) {
			require("error_db.php");
		}

		// Deletes this set of password reset tokens.
		try {
			sqlQuery("DELETE FROM pwd_reset WHERE salt1 = '" . $salt1 . "' AND salt2 = '" . $salt2 . "'");
		} catch (Exception $e) {
			require("error_db.php");
		}
		documentCreate("Password Reset", False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="blue">
		<strong>Password Reset</strong><br/>Check your email, a new password has been emailed to you.
	</div>
</div>
<?php writeFooter(); ?>
</div> <?php die();
	}

	// If no email address was returned = Invalid tokens.
	else { documentCreate("Password Reset", False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="red">
		<strong>Invalid Reset Tokens</strong><br/>The reset tokens you provided were either incorrect or have expired.
	</div>
</div>
<?php writeFooter(); ?>
</div> <?php die();
	}
}
?>
<?php documentCreate("Password Reset", False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
<h2>Need to reset your password?</h2>
<?php if($reset_error) {
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
		var x_email=document.forms["fm_reset"]["email"].value;

		if(x_email == "") {
			alert("You have to enter an email address!");
			return false;
		}
		if(!validateEmail(x_email)) {
			alert("The email address you provided was not valid.");
			return false;
		}
		return true;
	}
</script>
<form method="POST" name="fm_reset" onSubmit="return validateForm()">
	<p>Email Address:<br/><input type="email" name="email" class="txtglow" size="45"/></p>
	<p><input type="submit" name="submit" value="Reset your password" class="btn" id="blue"/></p>
</form>
</div>
<?php writeFooter(); ?>
</div>