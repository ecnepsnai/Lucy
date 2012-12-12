<?php
	require("session.php");
	if($usr_IsSignedIn == True){
		if($usr_Type == "Admin") {
			die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "admin\dash.php\">Redirecting...");
		} else {
			die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
		}
	}
	documentCreate("Welcome", False);
?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<h2>Welcome to Lucy</h2>
	<p>Lucy is an easy to use support system that lets developers better assist their users.</p>
</div>
<?php writeFooter(); ?>
</div>