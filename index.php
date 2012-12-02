<?php
	require("session.php");
	if($usr_IsSignedIn == True){
		if($usr_Type == "Admin") {
			die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "admin\dash.php\">Redirecting...");
		} else {
			die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
		}
	}
?>
<!doctype html>
<title>Welcome<?php echo(TITLE_SEPARATOR . TITLE_MAIN); ?></title>
<link rel="stylesheet" href="img/loader.css">
<link href="img/styles.css" rel="stylesheet" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<div id="wrapper">
<?php require("mdl_header.php"); ?>
<div id="content">
	<h2>Welcome to Lucy</h2>
	<p>Lucy is an easy to use support system that lets developers better assist their users.</p>
</div>
<?php require("mdl_footer.php"); ?>
</div>