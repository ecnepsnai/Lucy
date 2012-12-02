<?php
    require_once("defines.php"); //@nyl0x -- ensures this is always loaded
	$usr_IsSignedIn = False;
	session_start();
	if(isset($_SESSION['id']) || isset($_SESSION['name']) || isset($_SESSION['type'])){
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_EXPIRE)) {
			session_unset();
			session_destroy();
		}
		$_SESSION['LAST_ACTIVITY'] = time();
		$usr_IsSignedIn = True;
		$usr_ID = $_SESSION['id'];
		$usr_Name = $_SESSION['name'];
		$usr_Email = $_SESSION['email'];
		$usr_Type = $_SESSION['type'];
	}
	if(isset($_GET['t'])){
		echo("User is signed in: " . $usr_IsSignedIn);
		echo("<br/>User ID: " . $usr_ID);
		echo("<br/>User Name: " . $usr_Name);
		echo("<br/>User Email: " . $usr_Email);
		echo("<br/>User Type: " . $usr_Type);
	}
?>