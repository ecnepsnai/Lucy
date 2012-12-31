<?php
	require("assets/lib/session.php");
	if(!$usr_IsSignedIn){
		//redirects a anonymous user.
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?notice=login\">Redirecting...");
	}
	require("assets/lib/sql.php");
	if($usr_Type == "Admin"){
		$sql = "SELECT * FROM ticketlist";
	} else {
		$sql = "SELECT * FROM ticketlist WHERE name = '" . $usr_Name . "' AND email ='" . $usr_Email . "'";
	}
	try {
		$tickets = sqlQuery($sql, False);
	} catch (Exception $e) {
		require("error_db.php");
	}
?>
<?php documentCreate(TITLE_DASH, False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	
	<?php

		// Renders the page for an administrator
		if($usr_Type == "Admin"){
			if(count($tickets) == 0){
				echo('<h2>There are no open tickets in Lucy\'s database.</h2>');
			} else {
				echo("<h2>Tickets that need your attention:</h2>");

				// Writes out all them open tickets.
				foreach ($tickets as $ticket_info) {?>
					<div class="ticket_info" onClick="parent.location='<?php echo(SERVER_DOMAIN . "ticket.php?id=" . $ticket_info['id']); ?>'" style="cursor:pointer">
					<strong>Ticket ID:</strong> <?php echo($ticket_info['id']); ?><br/>
					<strong>Application:</strong> <?php echo($ticket_info['application']); ?><br/>
					<strong>Version:</strong> <?php echo($ticket_info['version']); ?><br/>
					<strong>Operating System:</strong> <?php echo($ticket_info['os']); ?><br/>
					<strong>Status:</strong> <?php echo($ticket_info['status']); ?><hr/>
					<?php echo($ticket_info['subject'] . '...'); ?>
					</div><?php
				}
			}
		}


		// Renders the page for an client
		if($usr_Type == "Client"){
			if(count($tickets) == 0){
				echo('<h2>You have no tickets.</h2><p><a href="new_ticket.php">Need to make a new one?</a>');
			} else {
				echo("<h2>Your tickets:</h2>");

				// Writes out all them open tickets.
				foreach ($tickets as $ticket_info) {?>
					<div class="ticket_info" onClick="parent.location='<?php echo(SERVER_DOMAIN . "ticket.php?id=" . $ticket_info['id']); ?>'" style="cursor:pointer">
					<strong>Ticket ID:</strong> <?php echo($ticket_info['id']); ?><br/>
					<strong>Application:</strong> <?php echo($ticket_info['application']); ?><br/>
					<strong>Version:</strong> <?php echo($ticket_info['version']); ?><br/>
					<strong>Operating System:</strong> <?php echo($ticket_info['os']); ?><br/>
					<strong>Status:</strong> <?php echo($ticket_info['status']); ?><hr/>
					<?php echo($ticket_info['subject'] . '...'); ?>
					</div><?php
				}
			}
		} ?>
</div>
<?php writeFooter(); ?>
</div>