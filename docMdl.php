<?php

	// Writes the header tags for the document.
	// $title 			= [string]			 The title of the page
	// $include_jQ 		= [bool]			 If true, function will include jQuery in the head.
	function documentCreate($title, $include_jQ){ ?>
<!doctype html>
<meta charset="utf-8">
<!--[if lt IE 9]>
<script src="<?php echo(SERVER_DOMAIN); ?>assets/js/html5shiv.js"></script>
<![endif]-->
<title><?php echo($title . TITLE_SEPARATOR . TITLE_MAIN) ?></title>
<link rel="stylesheet" href="<?php echo(SERVER_DOMAIN); ?>assets/img/loader.css">
<link href="<?php echo(SERVER_DOMAIN); ?>assets/img/styles.css" rel="stylesheet" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo(SERVER_DOMAIN); ?>assets/img/favicon.ico">
<?php if($include_jQ == True) { ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<?php }
}



	// Writes the header and navigation for the page.
	function writeHeader(){ ?>
<header>
	<div id="title">
		<a href="<?php echo(SERVER_DOMAIN); ?>index.php"><img src="<?php echo(SERVER_DOMAIN); ?>assets/img/header_logo.png" alt="Lucy"/></a>
	</div>
	<?php
		if(usr_IsSignedIn == True){
			echo('<div id="nav">');
			echo("Hey, " . usr_Name . "! ");
			if(usr_Type == "Admin"){ 
				//Navigation menu for users who are signed in and are administrators.
			?>
				<a href="<?php echo(SERVER_DOMAIN); ?>dash.php">All Tickets</a> | <a href="<?php echo(SERVER_DOMAIN); ?>admin/users.php">Users</a> | <a href="<?php echo(SERVER_DOMAIN); ?>admin/config.php">Lucy Settings</a> | <a href="<?php echo(SERVER_DOMAIN); ?>logout.php">Log Out</a></div>
			<?php
		} else {
				//Navigation for regular users.
			?>
				<a href="<?php echo(SERVER_DOMAIN); ?>dash.php">My Tickets</a> | <a href="<?php echo(SERVER_DOMAIN); ?>new_ticket.php">New Ticket</a> | <a href="<?php echo(SERVER_DOMAIN); ?>profile.php">My Profile</a> | <a href="<?php echo(SERVER_DOMAIN); ?>logout.php">Log Out</a></div>
			<?php
		} } else {
				//Navigation for anonymous users.
			?>
	<form method="post" action="<?php echo(SERVER_DOMAIN); ?>login.php">
		<table>
			<tr>
				<td>
					Email:<br/><input type="email" name="email" tabindex="1" maxlength="45" size="25"/><br/>
					<a href="<?php echo(SERVER_DOMAIN); ?>signup.php">Sign up</a>
				</td>
				<td>
					Password:<br/><input type="password" name="pwd" tabindex="2" size="25"/><br/>
					<a href="<?php echo(SERVER_DOMAIN); ?>forgot.php">Forgot Password</a>
				</td>
				<td>
					&nbsp;<br/><input type="submit" value="Login" name="submit" tabindex="3"/><br/>&nbsp;
				</td>
			</tr>
		</table>
	</form>
			<?php
		} ?>
</header>
<?php }




	// Writes the footer for the page.
	// Note: While you don't have to, I would appreciate it if you displayed the "Powered by the Lucy Framework" tag.
	function writeFooter(){ ?>
<footer>
	<p class="left"><?php echo(FOOTER_COPYRIGHT); ?></p>
	<p class="right">Powered by the <a href="//ianspence.com/lucy">Lucy Framework</a>.</p>
</footer>
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