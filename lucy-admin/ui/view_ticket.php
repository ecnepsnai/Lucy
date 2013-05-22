<?php
	include_once("../session.php");
	include_once("../sql.php");
	include_once("../mailer.php");
	include_once("default.php");

	// Administrator or Agent access only.
	if($usr_Type != "Admin" && $usr_Type != "Agent"){
		lucy_die(0);
	}

	// If no id was supplied.
	$id = $_GET['id'];
	if(empty($id)){
		die("No ID");
	}
	$id = addslashes($id);


	// User chose to close the ticket.
	if(isset($_POST['close']) && $_POST['close'] == "CloseTicket"){
		// Updates the master ticketlist.
		$sql = "UPDATE ticketlist SET status = 'Closed' WHERE  id = '" . $id . "';";
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}

		// Inserts a CLOSED message into the ticket table.
		$sql = "INSERT INTO " . $id . " (`Name`, `Email`, `Date`, `Message`, `From`) VALUES ('" . $usr_Name . "', '" . $usr_Email . "', '" . date("Y-m-d H:i:s")  . "', 'CLOSED', '";
		if($usr_Type == "Admin"){
			$sql.= "Agent');";
		} else {
			$sql.= "Client');";
		}
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}
	}

	// User assigned ticket
	if(isset($_POST['assign']) && isset($_POST['assignto'])){
		// Updates the master ticketlist.
		$sql = "UPDATE ticketlist SET assignedto = '" . addslashes($_POST['assignto']) . "' WHERE  id = '" . $id . "';";
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}
	}

	// User added a reply
	if(isset($_POST['reply']) && $_POST['reply'] == "ReplyToTicket"){

		// If no message was included.
		if(empty($_POST['message'])){
			die("Please include a message.");
		}
		$message = addslashes($_POST['message']);

		//Trims the message to the maximum length of MEDIUMTEXT.
		//IE and Opera don't support the maxlength attribute for textarea, so this is the fallback.
		$message = substr($message, 0, 16777216);

		$isFile = False;
		$filename = $_FILES['screenshot']['tmp_name'];


		// Tests to see if a screenshot was included.
		if (empty($filename)) {
			$img_hash = "";
		} elseif (isset($filename) && $GLOBALS['config']['Imgur']['Enable']) {

			// Getting the file information.
			$isFile = True;
			$handle = fopen($filename, "r");
			$data = fread($handle, filesize($filename));
			$pvars = array('image' => base64_encode($data), 'key' => $GLOBALS['config']['Imgur']['Key']);
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
		$sql = "INSERT INTO " . $id . " (`Name`, `Email`, `Date`, `Message`, `File`, `From`) VALUES ('" . $usr_Name . "', '" . $usr_Email . "', '" . date("Y-m-d H:i:s")  . "', '" . $message . "', '" . $img_hash . "', '";
		if($usr_Type == "Admin"){
			$sql.= "Agent');";
		} else {
			$sql.= "Client');";
		}
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}

		$sql = "UPDATE ticketlist SET lastreply = 'Agent' WHERE  id = '" . $id . "';";
		try {
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}

		
	}

	// Getting the ticket information from the master ticketlist.
	$sql = "SELECT * FROM ticketlist WHERE id = '" . $id . "'";

	try {
		$ticket_info = sqlQuery($sql, True);
	} catch (Exception $e) {
		die($e);
	}

	// If no data was returned -- no ticket does not exist.
	if(count($ticket_info) == 0){
		die();
	}



	// Getting everything from the ticket table.
	$sql = "SELECT * FROM " . $id;
	try {
		$ticket_messages = sqlQuery($sql, False);
	} catch (Exception $e) {
		die($e);
	}

	// THIS SHOULD NOT RETURN TRUE
	// If the ticket table has no data in it.
	if(count($ticket_messages) == 0){
		die("No Ticket Information");
	}


	// Mailer operations
	if(isset($_POST['close']) && $_POST['close'] == "CloseTicket"){
		// Sends update email.
		//mailer_ticketUpdate($ticket_info['name'], $ticket_info['email'], $ticket_info['id']);
	}
	if(isset($_POST['reply']) && $_POST['reply'] == "ReplyToTicket"){
		// Sends update email.
		//mailer_ticketUpdate($ticket_info['name'], $ticket_info['email'], $ticket_info['id']);
	}

	// Getting the "Assinged To" options
	$sql = "SELECT name, id FROM userlist WHERE type = 'Admin' OR type = 'Agent'";
	try {
		$assigned_options = sqlQuery($sql, False);
	} catch (Exception $e) {
		die($e);
	}


	getHeader("View Ticket");
?>
<link href="assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script src="assets/js/bootstrap.fileupload.js"></script>
<?php
	getNav(2);
?>
			<h2>Ticket Information</h2>
			<table class="table">
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
			<h2>Ticket Messages</h2>

			<?php
				foreach($ticket_messages as $message){
					// If the message was from the Client.
					if($message['From'] == "Client"){

						// If the message is the word CLOSED, the ticket has been closed.
						if($message['Message'] == "CLOSED") { ?>
			<div class="alert alert-warning"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($ticket_info['name']); ?> closed this ticket.</strong></div>
								<?php }

								// The message was not CLOSED, writing the message and screenshot (if any)
								else { ?>
			<div class="alert alert-notice">
				<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($ticket_info['name']); ?> said:</strong><br/><?php echo($message['Message']); ?>
				<?php if($message['File'] != ""){ ?>
				<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
					<?php } ?>
			</div><?php } }


			// The message was from an agent.
			else { 

				// In the message if the word CLOSED, the ticket has been closed.
				if($message['Message'] == "CLOSED") { ?>
					<div class="alert alert-warning"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($message['Name']); ?> closed this ticket.</strong></div>
								<?php }

								// The message was not CLOSED, writing the message and screenshot (if any)
								else { ?>
			<div class="alert alert-success">
				<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($message['Name']); ?> said:</strong><br/><?php echo($message['Message']); ?>
				<?php if($message['File'] != ""){ ?>
				<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
					<?php } ?>
			</div><?php } } } ?>
			<h2>Ticket Options</h2>
			<script type="text/javascript">
			function hideTools() {
				$('#ticket_options').hide();
				$('#ticket_reply').show();
			}
			</script>
				<div id="buttons">
				<div id="ticket_options">
					<form method="POST" class="form-horizontal">
						<button type="button" onclick="parent.location='javascript:hideTools()'" value="Reply to this ticket" class="btn" id="gray">Reply to this ticket</button>
						<input type="hidden" name="close" value="CloseTicket"/><input type="submit" name"submit" value="Close this ticket" class="btn" id="gray"/>
					</form>
					<form method="POST" class="form-horizontal">
						Assign To: 
						<select name="assignto"><?php foreach ($assigned_options as $user) { echo('<option value="' . $user['id'] . '" '); if($ticket_info['assignedto'] == $user['id']){ echo('disabled="disabled"'); } echo('>' . $user['name'] . '</option>'); } ?></select>
						<input type="submit" name="assign" value="Assign Ticket" class="btn" />
					</form>
				</div>
				<div id="ticket_reply" style="display:none">
					<form method="POST" enctype="multipart/form-data" class="form-horizontal">
						<input type="hidden" name="reply" value="ReplyToTicket"/>
						<div class="control-group">
							<label class="control-label">Your Reply:</label>
							<div class="controls">
								<textarea name="message" rows="10" cols="75" class="txtglow" placeholder="Type your reply here" maxlength="16777216"></textarea><br/>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Screenshot:</label>
							<div class="controls">
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="screenshot"/></span>
									<span class="fileupload-preview"></span>
									<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
								</div>
							</div>
						</div>
						<input type="submit" name"reply" value="Add Reply" class="btn btn-primary" id="blue"/>
					</form>
				</div>
			</div>
	<?php getFooter(); ?>