<?php
	require("session.php");
	if($usr_IsSignedIn == True){
		if($usr_Type == "Admin") {
			//redirects an admin user.
			die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "admin\dash.php\">Redirecting...");
		}
	} else {
		//redirects a anonymous user.
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?notice=login\">Redirecting...");
	}
	require("db_connect.php");
	$sql = "SELECT * FROM ticketlist WHERE name = '" . $usr_Name . "' AND email ='" . $usr_Email . "' AND status = 'Open'";
	$request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}
?>
<!doctype html>
<title><?php echo(TITLE_DASH . TITLE_SEPARATOR . TITLE_MAIN); ?></title>
<link rel="stylesheet" href="img/loader.css">
<link href="img/styles.css" rel="stylesheet" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<div id="wrapper">
<?php require("mdl_header.php"); ?>
<div id="content">
	
	<?php 
		if(mysql_num_rows($request) == 0){
			echo('<h2>You have no open tickets.</h2><p><a href="new_ticket.php">Need to make a new one?</a>');
		} else {
			echo('<h2>Your open tickets</h2>');
			while($ticket_info = mysql_fetch_array($request)) { ?>
				<div class="ticket_info" onClick="parent.location='<?php echo(SERVER_DOMAIN . "ticket.php?id=" . $ticket_info['id']); ?>'" style="cursor:pointer">
				<strong>Ticket ID:</strong> <?php echo($ticket_info['id']); ?><br/>
				<strong>Application:</strong> <?php echo($ticket_info['application']); ?><br/>
				<strong>Version:</strong> <?php echo($ticket_info['version']); ?><br/>
				<strong>Operating System:</strong> <?php echo($ticket_info['os']); ?><br/>
				<strong>Status:</strong> <?php echo($ticket_info['status']); ?><hr/>
				<?php echo($ticket_info['subject']); ?>
				</div>
	<?php } } ?>
</div>
<?php require("mdl_footer.php"); ?>
</div>