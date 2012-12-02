<div id="header">
	<div id="title">
		<a href="<?php echo(SERVER_DOMAIN); ?>index.php"><img src="<?php echo(SERVER_DOMAIN); ?>img/header_logo.png" alt="Lucy"/></a>
	</div>
	<div id="nav">
		<?php
			if($usr_IsSignedIn == True){
				echo("Hey, " . $usr_Name . "! ");
				if($usr_Type == "Admin"){ 
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