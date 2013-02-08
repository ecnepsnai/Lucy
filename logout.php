<?php
require("lucy-admin/session.php");
	session_start();
	session_unset();
	session_destroy();
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . $GLOBLAS['config']['Domain'] . "index.php\">Redirecting...");
?>