<?php
	require("session.php");
	if(!$usr_IsSignedIn){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?rdirect=ticket.php?id=" . $_GET['id'] . "&notice=login\">Redirecting...");
	}
	$id = $_GET['id'];
	if(empty($id)){
		require("error_empty.php");
	}
	require("db_connect.php");
	$id = mysql_real_escape_string($id);
	$sql = "SELECT * FROM ticketlist WHERE id = '" . $id . "' AND email = '" . $usr_Email . "'";
	$request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}
	if(mysql_num_rows($request) <= 0){
		require("error_empty.php");
	}
	$ticket_info = mysql_fetch_array($request);
	$sql = "SELECT * FROM " . $id;
	$request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}
	if(mysql_num_rows($request) <= 0){
		require("error_empty.php");
	}
?>
<!doctype html>
<title><?php echo(TITLE_TICKET . TITLE_SEPARATOR . TITLE_MAIN) ?></title>
<link rel="stylesheet" href="img/loader.css">
<link href="img/styles.css" rel="stylesheet" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<div id="wrapper">
<?php require("mdl_header.php"); ?>
<div id="content">
<h2>Ticket Status</h2>
<table id="ticket_info">
	<tr>
		<td>
			<strong>Application:</strong> <?php echo($ticket_info['application']); ?><br/>
			<strong>Version:</strong> <?php echo($ticket_info['version']); ?><br/>
			<strong>Operating System:</strong> <?php echo($ticket_info['os']); ?><br/>
			<strong>Status:</strong> <?php echo($ticket_info['status']); ?>
		</td>
		<td>
			<strong>Ticket ID:</strong> <?php echo($ticket_info['id']); ?><br/>
			<strong>Name:</strong> <?php echo($ticket_info['name']); ?><br/>
			<strong>Email:</strong> <?php echo($ticket_info['email']); ?><br/>
			<strong>Date Created:</strong> <?php echo(date_format(date_create($ticket_info['date']), 'l, F jS \a\t g:i a')); ?> 
		</td>
	</tr>
</table>
<?php
	while($message = mysql_fetch_array($request)) {
		if($message['From'] == "Client"){
				if($message['Message'] == "CLOSED") {
					?>
<div class="message" id="yellow"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> you closed this ticket.</strong></div>
					<?php
				} else { ?>
<div class="msgc">
	<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> you said:</strong><br/><?php echo($message['Message']); ?>
	<?php if($message['File'] != ""){
		?>
	<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
		<?php
	} ?>
</div><?php } } else {
				if($message['Message'] == "CLOSED") {
					?>
		<div class="message" id="yellow"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> Lucy closed this ticket.</strong></div>
					<?php
				} else { ?>
<div class="msga">
	<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> Lucy said:</strong><br/><?php echo($message['Message']); ?>
	<?php if($message['File'] != ""){
		?>
	<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
		<?php
	} ?>
</div><?php } } } ?>
<div id="ticket_options">
	You can <button onPress="" value="Reply to this ticket">Reply to this ticket</button> or <form method="POST" style="display:inline"><input type="submit" name"close" value="Close this ticket"/></form>
</div>
</div>
<?php require("mdl_footer.php"); ?>
</div>