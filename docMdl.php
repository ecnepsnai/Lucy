<?php

	// Writes the header tags for the document.
	// $title 			= [string]			 The title of the page
	// $include_jQ 		= [bool]			 If true, function will include jQuery in the head.
	// $seo_write 		= [bool]			 If True, function will include the SEO tags.
	// $seo_description = [optional /string] The description used for SEO.  Will use generic description if no value is provided.
	// $seo_tags 		= [optional /string] The tags used for SEO.  Will use generic tags if no value is provided.
	function documentCreate($title, $include_jQ, $seo_write, $seo_description, $seo_tags){ ?>
<!doctype html>
<meta charset="utf-8">
<!--[if lt IE 9]>
<script src="<?php echo(SERVER_DOMAIN); ?>assets/js/html5shiv.js"></script>
<![endif]-->
<title><?php echo($title . TITLE_SEPARATOR . TITLE_MAIN) ?></title>
<link rel="stylesheet" href="<?php echo(SERVER_DOMAIN); ?>img/loader.css">
<link href="<?php echo(SERVER_DOMAIN); ?>img/styles.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo(SERVER_DOMAIN); ?>img/favicon.ico">
<?php if($include_jQ == True) { ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<?php } if($seo_write == True) {
if(empty($seo_description)){$seo_description = "We are a small software development group run out on Vancouver, BC.";}
if(empty($seo_tags)){$seo_tags = "web design, software development, web development, mobile development";}
?>
<meta name="description" content="<?php echo($seo_description); ?>"/>
<meta name="keywords" content="<?php echo($seo_tags); ?>"/>
<?php } }



	// Writes the header and navigation for the page.
	function writeHeader(){ ?>
<div id="header">
	<div id="title">
		<a href="<?php echo(SERVER_DOMAIN); ?>index.php"><img src="<?php echo(SERVER_DOMAIN); ?>img/header_logo.png" alt="Lucy"/></a>
	</div>
	<div id="nav">
		<?php
			if(usr_IsSignedIn == True){
				echo("Hey, " . usr_Name . "! ");
				if(usr_Type == "Admin"){ 
					//Navigation menu for users who are signed in and are administrators.
				?>
					<a href="<?php echo(SERVER_DOMAIN); ?>admin/dash.php">My Tickets</a> | <a href="<?php echo(SERVER_DOMAIN); ?>admin/tickets.php?s=0">All Tickets</a> | <a href="<?php echo(SERVER_DOMAIN); ?>admin/users.php">Users</a> | <a href="<?php echo(SERVER_DOMAIN); ?>admin/config.php">Lucy Settings</a> | <a href="<?php echo(SERVER_DOMAIN); ?>logout.php">Log Out</a>
				<?php
			} else {
					//Navigation for regular users.
				?>
					<a href="<?php echo(SERVER_DOMAIN); ?>dash.php">My Tickets</a> | <a href="<?php echo(SERVER_DOMAIN); ?>new_ticket.php">New Ticket</a> | <a href="<?php echo(SERVER_DOMAIN); ?>profile.php">My Profile</a> | <a href="<?php echo(SERVER_DOMAIN); ?>logout.php">Log Out</a>
				<?php
			} } else {
					//Navigation for anonymous users.
				?>
					<a href="<?php echo(SERVER_DOMAIN); ?>signup.php">Sign Up</a> | <a href="<?php echo(SERVER_DOMAIN); ?>login.php">Log In</a>
				<?php
			} ?>
	</div>
</div>
<?php }




	// Writes the footer for the page.
	function writeFooter(){ ?>
<div id="footer">Copyright &copy; Ian Spence 2012.  Powered by the <a href="http://ianspence.com/lucy">Lucy Framework</a>.</div>
<?php }



	// Writes the redirection elements.
	// $anon 			= [bool]		If true, anonymous users can view the page.
	// $user 			= [bool]		If true, users who are signed up, but not administrators, can view the page.
	// $admin 			= [bool]		If true, users who are administrators can view the page.
	function writeRedirect($anon, $user, $admin) {
		if(!$anon && !usr_IsSignedIn){
			die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?notice=login\">Redirecting...");
		}

		if(!$user && usr_Type == "Client"){
			require("error_auth.php");
		}

		if(!$admin && usr_Type == "Admin"){
			require("error_auth.php");
		}
	}