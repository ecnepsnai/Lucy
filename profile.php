<?php
require("lucy-admin/session.php");
require("lucy-admin/sql.php");

if(!$usr_IsSignedIn){
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . $GLOBALS['Config']['domain'] . "login.php\">Redirecting...");
}

if(isset($_POST['submit'])){
	if(isset($_POST['cur_password'])){
		$current_password = addslashes($_POST['cur_password']);
		$new_password = addslashes($_POST['new_password']);
		$new_password_rep = addslashes($_POST['new_password_rep']);
		if($new_password != $new_password_rep){
			die("Passwords do not match.");
		}
		$sql = "SELECT salt FROM userlist WHERE id = '" . $usr_ID . "'";
		try {
			$user = sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}

		$hashed_current_password = md5($user['salt'] . md5($current_password));
		$salt = mt_rand(10, 99);
		$hashed_new_password = md5($salt . md5($new_password));
		$sql = "UPDATE userlist SET password = '" . $hashed_new_password . "', salt = '" . $salt . "' WHERE password = '" . $hashed_current_password . "';";
		try {
			sqlQuery($sql, False);
		} catch (Exception $e) {
			die($e);
		}
	}
}
$sql = "SELECT name, email FROM userlist WHERE id = '" . $usr_ID . "'";
try {
	$user = sqlQuery($sql, True);
} catch (Exception $e) {
	die($e);
}
require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/profile.php');