<?php
require("lucy-admin/session.php");
	session_start();
	session_unset();
	session_destroy();
	header("Location: " . $GLOBALS['Config']['domain'] . "index.php");
?>