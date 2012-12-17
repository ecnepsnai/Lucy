<?php
    require_once("defines.php");
    require("docMdl.php");
	$usr_IsSignedIn = False;
	session_start();
	if(isset($_SESSION['id']) || isset($_SESSION['name']) || isset($_SESSION['type'])){
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_EXPIRE)) {
			session_unset();
			session_destroy();
		}
		$_SESSION['LAST_ACTIVITY'] = time();

		// We need to define these variables so they are available on the global scope.
		$usr_ID = $_SESSION['id'];
		$usr_Name = $_SESSION['name'];
		$usr_Email = $_SESSION['email'];
		$usr_Type = $_SESSION['type'];

		define(usr_ID, $_SESSION['id']);
		define(usr_Name, $_SESSION['name']);
		define(usr_Email, $_SESSION['Email']);
		$usr_IsSignedIn = True;
		define(usr_Type, $_SESSION['type']);
	}
	if(isset($_GET['t'])){
		echo("User is signed in: " . $usr_IsSignedIn);
		echo("<br/>User ID: " . $usr_ID);
		echo("<br/>User Name: " . $usr_Name);
		echo("<br/>User Email: " . $usr_Email);
		echo("<br/>User Type: " . $usr_Type);
	}
	define(usr_IsSignedIn, $usr_IsSignedIn);
?>