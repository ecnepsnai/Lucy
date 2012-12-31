<?php
	require("assets/lib/session.php");
	if($usr_IsSignedIn == True){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
	}
	documentCreate("Welcome", False);
?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<h1>Welcome to Lucy</h1>
	<p>Lucy is an easy to use support system that lets developers better assist their users.</p>
</div>
<?php writeFooter(); ?>
</div>