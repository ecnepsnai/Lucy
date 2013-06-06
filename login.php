<?php
require("lucy-admin/session.php");
require("lucy-admin/sql.php");
$login_error = False;

// Obviously if the user is already signed in, we don't let them log in again.
if($usr_IsSignedIn){
	header("Location: dash.php");
}

// Requires the captcha library if reCAP is enabled.
if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Login']){
	require("lucy-admin/recaptchalib.php");
}

// User chose to login.
if(isset($_POST['submit'])){
	// Validates the reCAPTICHA challenge if enabled.
	if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Login']){
		$resp = recaptcha_check_answer ($GLOBALS['config']['ReCaptcha']['Private'], $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			$cap_error = True;
			goto writeDOC;
		}
	}


	// Getting the raw inputs.
	$raw_email = trim($_POST['email']);
	if(empty($raw_email) || empty($_POST['pwd'])){
		goto writeDOC;
	}

	// Preparing the first sql request.
	$inp_email = addslashes($raw_email);
	// Ask for user salt first, then verify, THEN get rest of data
	$sql = "SELECT email, salt FROM userlist WHERE email = '". $inp_email . "'";
	try {
		$pw = sqlQuery($sql, True);
	} catch (Exception $e) {
		require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/error-db.php');
	}
	$pw_hash = md5($pw['salt'] . md5(trim($_POST['pwd'])));

	// Preparing the second sql request.
	$sql = "SELECT id, name, type, email, tf_enable, tf_secret FROM userlist WHERE email = '". $inp_email ."' AND password = '" . $pw_hash . "'";
	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/error-db.php');
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

		// Testing to see if Two-Factor Authentication is Enabled
		if($user['tf_enable'] && $user['tf_secret'] != ""){
			session_start();
			$_SESSION['tf_secret'] = $user['tf_secret'];
			header("Location: auth.php");
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
				header("Location: " . $_GET['rdirect']);
			}

			// Moves the user to the administrator dashboard if they are an admin
			if($user['type'] == 'Admin' || $user['type'] == "Agent"){
				header("Location: lucy-admin/ui/");
			} else {
				header("Location: dash.php");
			}
		}
	}
}
writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/login.php');