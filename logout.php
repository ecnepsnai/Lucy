<?php
require("assets/lib/defines.php");
	session_start();
	session_unset();
	session_destroy();
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "index.php\">Redirecting...");
?>