<!doctype html>
<title>Error<?php echo(TITLE_SEPERATOR . TITLE_MAIN); ?></title>
<link rel="stylesheet" href="img/loader.css">
<link href="img/styles.css" rel="stylesheet" type="text/css">
<script src="js/jquery.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<div id="wrapper">
<?php require("mdl_header.php"); ?>
<div id="content">
	<div class="message" id="yellow">
		<strong>Authentication Error</strong>
		<p>Uh oh!  You need to be signed in to do that.</p>
	</div>
</div>
<?php require("mdl_footer.php"); ?>
</div><?php die(); ?>