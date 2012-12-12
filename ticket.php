<?php
	require("session.php");

	// This page requires a user to be signed in.
	if(!$usr_IsSignedIn){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?rdirect=ticket.php?id=" . $_GET['id'] . "&notice=login\">Redirecting...");
	}

	// If no id was supplied.
	$id = $_GET['id'];
	if(empty($id)){
		require("error_empty.php");
	}


	require("db_connect.php");
	$id = mysql_real_escape_string($id);


	// User chose to close the ticket.
	if(isset($_POST['close']) && $_POST['close'] == "CloseTicket"){

		// Updates the master ticketlist.
		$sql = "UPDATE ticketlist SET status = 'Closed' WHERE  id = '" . $id . "';";
		$request = mysql_query($sql);
		if(!$request){
			require("error_db.php");
		}

		// Inserts a CLOSED message into the ticket table.
		$sql = "INSERT INTO " . $id . " (`From`, `Email`, `Date`, `Message`) VALUES ('Client', '" . $usr_Email . "', '" . date("Y-m-d H:i:s")  . "', 'CLOSED');";
		$request = mysql_query($sql);
		if(!$request){
			require("error_db.php");
		}
	}

	// User added a reply
	if(isset($_POST['reply']) && $_POST['reply'] == "ReplyToTicket"){

		// If no message was included.
		if(empty($_POST['message'])){
			require("error_empty.php");
		}
		$message = mysql_real_escape_string($_POST['message']);

		//Trims the message to the maximum length of MEDIUMTEXT.
		//IE and Opera don't support the maxlength attribute for textarea, so this is the fallback.
		$message = substr($message, 0, 16777216);

		$isFile = False;
		$filename = $_FILES['screenshot']['tmp_name'];


		// Tests to see if a screenshot was included.
		if (empty($filename)) {
			$img_hash = "";
		} else {

			// Getting the file information.
			$isFile = True;
			$handle = fopen($filename, "r");
			$data = fread($handle, filesize($filename));
			$pvars = array('image' => base64_encode($data), 'key' => API_IMGUR);
			$timeout = 30;

			// Setting up the cUrl uploader.
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://api.imgur.com/2/upload.json');
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);

			// Uploading to Imgur.
			$json = curl_exec($curl);
			curl_close ($curl);
			$data = json_decode($json,true);

			// Getting the image hash from the response.
			$img_hash = $data["upload"]["image"]["hash"];
		}

		// Inserts the new entry into the ticket table.
		$sql = "INSERT INTO " . $id . " (From, Email, Date, Message,File) VALUES ('Client', '" . $usr_Email . "', '" . date("Y-m-d H:i:s")  . "', '" . $message . "', '" . $img_hash . "');";
		$request = mysql_query($sql);
		if(!$request){
			require("error_db.php");
		}
	}

	// Getting the ticket information from the master ticketlist.
	$sql = "SELECT application, version, os, status, id, name, email, date FROM ticketlist WHERE id = '" . $id . "'";

	// Administrator users can see all tickets.
	if($usr_Type != "Admin"){
		$sql.= " AND email = '" . $usr_Email . "'";
	}

	$request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}

	// If no data was returned -- no ticket does not exist.
	if(mysql_num_rows($request) != 1){ 
	documentCreate(TITLE_ERROR, False, False, null, null); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="yellow">
		<strong>Ticket not found!</strong><br/>
		Uh oh!  That ticket ID does not exist or you don't have permission to view it.
	</div>
</div>
<?php writeFooter(); ?>
</div><?php die();  }
	$ticket_info = mysql_fetch_array($request);

	// Getting everything from the ticket table.
	$sql = "SELECT * FROM " . $id;
	$request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}

	// THIS SHOULD NOT RETURN TRUE
	// If the ticket table has no data in it.
	if(mysql_num_rows($request) <= 0){
		require("error_empty.php");
	}

documentCreate(TITLE_TICKET, True, False, null, null); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
<h2>Ticket Status</h2>
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
<?php
	while($message = mysql_fetch_array($request)) {

		// If the message was from the Client.
		if($message['From'] == "Client"){

			// If the message is the word CLOSED, the ticket has been closed.
			if($message['Message'] == "CLOSED") { ?>
<div class="notice" id="yellow"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($ticket_info['name']); ?>closed this ticket.</strong></div>
					<?php }

					// The message was not CLOSED, writing the message and screenshot (if any)
					else { ?>
<div class="msgc">
	<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($ticket_info['name']); ?> said:</strong><br/><?php echo($message['Message']); ?>
	<?php if($message['File'] != ""){ ?>
	<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
		<?php } ?>
</div><?php } }


// The message was from an agent.
else { 

	// In the message if the word CLOSED, the ticket has been closed.
	if($message['Message'] == "CLOSED") { ?>
		<div class="notice" id="yellow"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> Lucy closed this ticket.</strong></div>
					<?php }

					// The message was not CLOSED, writing the message and screenshot (if any)
					else { ?>
<div class="msga">
	<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> Lucy said:</strong><br/><?php echo($message['Message']); ?>
	<?php if($message['File'] != ""){ ?>
	<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
		<?php } ?>
</div><?php } } }


// If the ticket is open, we display the ticket options.
	if($ticket_info['status'] == "Open") { 
		if($usr_Type == "Admin") { ?>Go to the <a href="">Admin Dashboard</a> to manage this ticket.<?php } else { ?>
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
<?php } } ?>
</div>
<?php writeFooter(); ?>
</div>