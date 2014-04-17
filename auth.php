<?php
require("lucy-admin/session.php");
require("lucy-admin/auth.php");
$auth_error = False;

// Obviously if the user is already signed in, we don't let them log in again.
if($usr_IsSignedIn){
	header("Location: dash.php");
}

// User entered a code.
if(isset($_POST['submit'])){
	switch ($_POST['submit']) {
		case 'Login':
			// Requiring the CDA library.
			require("lucy-admin/cda.php");

			// Creating the CDA class.
			$cda = new cda;
			// Initializing the CDA class.
			$cda->init($GLOBALS['config']['Database']['Type']);
			
			// Creating the TFA class
			$tf = new tfa;

			$codeIsValid = False;

			// Verifying the code
			try{
				$codeIsValid = $tf->verifyCode($_SESSION['tf_secret'], $_POST['pin'], 1);
			} catch (Exception $e){
				lucy_error('Invalid Code','Try again!');
				goto writeDOC;
			}

			// Code was not valid
			if(!$codeIsValid){
				$auth_error = true;
				goto writeDOC;
			}

			// Selecting the user information for the session
			try {
				$response = $cda->select(array("id","name","type","email"),"userlist",array("tf_secret"=>$_SESSION['tf_secret']));
			} catch (Exception $e) {
				lucy_error('Database Error',$e, true);
				goto writeDOC;
			}
			$user = $response['data'];

			// Creating the session data for the user.
			unset($_SESSION['tf_secret']);
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
		break;

		case 'Recover Account':
			// Requiring the CDA library.
			require("lucy-admin/cda.php");

			// Creating the CDA class.
			$cda = new cda;
			// Initializing the CDA class.
			$cda->init($GLOBALS['config']['Database']['Type']);


			// Ask for user salt first, then verify, THEN get rest of data
			try {
				$response = $cda->select(array("salt", "id"),"userlist",array("tf_secret"=>$_SESSION['tf_secret']));
			} catch (Exception $e) {
				lucy_error('Database Error',$e, true);
				goto writeDOC;
			}

			// Hashing the recovery code
			$pw = $response['data'];
			$pw_hash = md5($pw['salt'] . md5(trim($_POST['password'])));
			$id = $response['data']['id'];

			// Preparing the second sql request.
			try {
				$response = $cda->select(array("id","name","type","email"),"userlist",array("id"=>$id,"password"=>$pw_hash));
			} catch (Exception $e) {
				lucy_error('Database Error',$e, true);
				goto writeDOC;
			}

			// Verifying the recovery PIN
			$user = $response['data'];
			if(empty($user['id'])){
				lucy_error('Invalid Code','Try again!');
				goto writeDOC;
			}

			// Selecting the user information for the session
			try{
				$response = $cda->update("userlist", array("tf_backup"=>"","tf_secret"=>""),array("id"=>$id));
			} catch (Exception $e) {
				lucy_error('Database Error',$e, true);
				goto writeDOC;
			}

			// Creating the session data for the user.
			unset($_SESSION['tf_secret']);
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
			

		break;
	}
}
writeDOC:
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/auth.php');