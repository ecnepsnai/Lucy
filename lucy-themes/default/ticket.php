<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Ticket Status'); ?>
<h1>Ticket Status</h1>
<table class="ticket_info">
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
<h2>Ticket Messages:</h2>
<?php
	foreach($ticket_messages as $message){
		// If the message was from the Client.
		if($message['From'] == "Client"){

			// If the message is the word CLOSED, the ticket has been closed.
			if($message['Message'] == "CLOSED") { ?>
				<div class="ticket_message client"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($ticket_info['name']); ?> closed this ticket.</strong></div>
					<?php }

					// The message was not CLOSED, writing the message and screenshot (if any)
					else { ?>
<div class="ticket_message client">
	<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($ticket_info['name']); ?> said:</strong><br/><?php echo($message['Message']); ?>
	<?php if($message['File'] != ""){ ?>
	<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
		<?php } ?>
</div><?php } }


// The message was from an agent.
else { 

	// In the message if the word CLOSED, the ticket has been closed.
	if($message['Message'] == "CLOSED") { ?>
		<div class="ticket_message agent"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($message['Name']); ?> closed this ticket.</strong></div>
					<?php }

					// The message was not CLOSED, writing the message and screenshot (if any)
					else { ?>
<div class="ticket_message agent">
	<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($message['Name']); ?> said:</strong><br/><?php echo($message['Message']); ?>
	<?php if($message['File'] != ""){ ?>
	<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
		<?php } ?>
</div><?php } } }


// If the ticket is open, we display the ticket options.
	if($ticket_info['status'] == "Pending" || $ticket_info['status'] == "Active") { ?>
<hr/>
<h2>Ticket Options</h2>
<script type="text/javascript">
function hideTools() {
	$('#ticket_options').hide();
	$('#ticket_reply').show();
}
</script>
<div id="ticket_options">
	You can <button onclick="parent.location='javascript:hideTools()'" value="Reply to this ticket" class="btn" id="gray">Reply to this ticket</button> or <form method="POST" style="display:inline"><input type="hidden" name="close" value="CloseTicket"/><input type="submit" name"submit" value="Close this ticket" class="btn" id="gray"/></form>
</div>
<div id="ticket_reply" style="display:none">
	<form method="POST" enctype="multipart/form-data">
		<input type="hidden" name="reply" value="ReplyToTicket"/>
		<textarea name="message" rows="10" cols="75" class="txtglow" placeholder="Type your reply here" maxlength="16777216"></textarea><br/>
		<p>Include a screenshot? (<em>Optional</em> - <a href="help_screenshots.php">Help</a>)<br/>
		<input type="file" name="screenshot" /></p>
		<input type="submit" name"reply" value="Add Reply" class="btn" id="blue"/>
	</form>
</div>
<?php } ?>
<?php getFooter(); ?>