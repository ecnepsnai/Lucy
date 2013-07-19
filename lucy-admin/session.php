<?php
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	require_once("defines.php");
	require_once("mailer.php");
	$usr_IsSignedIn = False;
	session_start();
	if(isset($_SESSION['id']) || isset($_SESSION['name']) || isset($_SESSION['type'])){
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $GLOBALS['config']['SessionExpire'])) {
			session_unset();
			session_destroy();
		}
		$_SESSION['LAST_ACTIVITY'] = time();

		$usr_ID = $_SESSION['id'];
		$usr_Name = $_SESSION['name'];
		$usr_Email = $_SESSION['email'];
		$usr_Type = $_SESSION['type'];
		$usr_IsSignedIn = True;
	}
	if($_GET['r'] == "1"){
		print_r($_SESSION);
	}
?>