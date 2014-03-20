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

		$error = array();
		$error['title'] = $_SESSION['error_title'];
		$error['body'] = $_SESSION['error_body'];
		$error['fatal'] = $_SESSION['error_fatal'];
	}
	function lucy_error($title = null, $body = null, $fatal = null){
		if($title == null && $body == null && $fatal == null){
			$error = array();
			$error['title'] = $_SESSION['error_title'];
			$error['body'] = $_SESSION['error_body'];
			$error['fatal'] = $_SESSION['error_fatal'];
			$_SESSION['error_title'] = null;
			$_SESSION['error_body'] = null;
			$_SESSION['error_fatal'] = null;
			return $error;
		} else {
			$_SESSION['error_title'] = $title;
			$_SESSION['error_body'] = $body;
			$_SESSION['error_fatal'] = $fatal;
			$error = array();
			$error['title'] = $_SESSION['error_title'];
			$error['body'] = $_SESSION['error_body'];
			$error['fatal'] = $_SESSION['error_fatal'];
			return $error;
		}
	}
?>