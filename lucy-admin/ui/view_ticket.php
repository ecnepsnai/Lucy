<?php
	include_once("../session.php");
	include_once("../cda.php");
	include_once("../mailer.php");
	include_once("default.php");

	// Administrator or Agent access only.
	if($usr_Type != "Admin" && $usr_Type != "Agent"){
		lucy_die(0);
	}

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// If no id was supplied.
	$id = $_GET['id'];
	if(empty($id)){
		die("No ID");
	}
	$id = addslashes($id);


	try {
		$response = $cda->select(null,"ticketlist",array("id"=>$id));
	} catch (Exception $e) {
		die($e);
	}
	$ticket_info = $response['data'];

	// If no data was returned -- no ticket does not exist.
	if(count($ticket_info) == 0){
		die();
	}


	try {
		$response = $cda->select(null,$id,null);
	} catch (Exception $e) {
		die($e);
	}
	$ticket_messages = $response['data'];

	// Correcting issue if there is only one item in the database.
	if(isset($ticket_messages['From'])){
		$ticket_messages = array($ticket_messages);
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

	getHeader("View Ticket");
?>
<link href="assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script src="assets/js/bootstrap-fileupload.js"></script>
<link href="assets/css/bootstrap-modal.css" rel="stylesheet">
<script src="assets/js/bootstrap-modal.js"></script>
<script src="assets/js/bootstrap-modalmanager.js"></script>
<?php
	getNav(2);
?>
<script type="text/javascript">

	var postRequest = $.post("../api/admin_get_users.php");

	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			alert(obj.response.message);
		} else {
			var length = obj.response.data.users.length;
			var isassignedto = <?php echo($ticket_info['assignedto']); ?>;
			for (var i = 0; i < length; i++) {
				var html = '<option ';
				if(obj.response.data.users[i].id == isassignedto){
					html = html + 'selected="selected" ';
				}
				html = html + 'value="' + obj.response.data.users[i].id + '">' + obj.response.data.users[i].name + '</option>';
				$("#assignmentSelection").append(html);
			}
		}
	});

	function closeTicket() {
		$("#closeBtns").hide();
		$("#closeWait").show();
		var postRequest = $.post("../api/ticket_close.php", {
			ownerID: "<?php echo($ticket_info['owner']); ?>", 
			id: "<?php echo($ticket_info['id']); ?>"
		});

		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#replyBtns").show();
				$("#replyWait").hide();
			} else {
				window.location.reload();
			}
		});
	}
	function addReply() {
		$("#replyBtns").hide();
		$("#replyWait").show();

		if($("#message").val() == "CLOSED"){
			alert("Illegal message body.");
			$("#replyBtns").show();
			$("#replyWait").hide();
			return 0;
		}

		var reader = new FileReader();
		reader.readAsBinaryString(document.getElementsByName("screenshot")[0].files[0]);
		var data = null;
		reader.onload = function(e) {
			data = btoa(e.currentTarget.result);
		}

		var postRequest = $.post("../api/ticket_update.php", {
			ownerID: "<?php echo($ticket_info['owner']); ?>", 
			id: "<?php echo($ticket_info['id']); ?>",
			message: $("#message").val(),
			image: data
		});

		console.log("Creating request.  Data is as follows:");
		console.log("Owner ID: <?php echo($ticket_info['owner']); ?>");
		console.log("Ticket ID: <?php echo($ticket_info['id']); ?>");
		console.log("Message: " + $("#message").val());

		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			console.log(obj);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#replyBtns").show();
				$("#replyWait").hide();
			} else {
				$('#replyModal').modal('hide');
				$('#ticketMessages').append('<div class="alert alert-success"><strong>On <?php echo(date('l, F jS \a\t g:i a')); ?> you said:</strong><br>' + $("#message").val() + '</div>');
			}
		});
	}
	function updateTicket() {
		$("#updateBtns").hide();
		$("#updateWait").show();
		var postRequest = $.post("../api/admin_edit_ticket.php", {
			assignment: document.getElementsByName("assignmentSelection")[0].value,
			status: $('input[name="statusSelection"]:checked').val(),
			id: "<?php echo($ticket_info['id']); ?>"
		});

		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#updateBtns").show();
				$("#updateWait").hide();
			} else {
				window.location.reload();
			}
		});
	}
	function showMessageModal(messageID) {
		$("#editreplybtn").attr('onclick','editMessage(' + messageID + ')');
		$("#editReplyMessage").val($('#reply_ID_' + messageID).html());
		$('#replyModal').modal('hide');
		$('#editMessageModal').modal('show');
	}
	function editMessage(messageID) {
		$("#editMessageBtns").hide();
		$("#editMessageWait").show();
		var postRequest = $.post("../api/admin_edit_message.php", {
			message: $("#editReplyMessage").val(),
			ticketid: "<?php echo($ticket_info['id']); ?>",
			updateid: messageID
		});

		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#editMessageBtns").show();
				$("#editMessageWait").hide();
			} else {
				$('#reply_ID_' + messageID).html($("#editReplyMessage").val());
				$('#editMessageModal').modal('hide');
				$("#editMessageBtns").show();
				$("#editMessageWait").hide();
			}
		});
	}
</script>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span3">
			<h4>Ticket Info</h4>
			<div class="alert alert-info">
				<strong>Application:</strong> <?php echo($ticket_info['application']); ?><br/>
				<strong>Version:</strong> <?php echo($ticket_info['version']); ?><br/>
				<strong>Operating System:</strong> <?php echo($ticket_info['os']); ?><br/>
				<strong>Status:</strong> <?php echo($ticket_info['status']); ?><br/>
				<strong>Date Created:</strong> <?php echo(date_format(date_create($ticket_info['date']), 'l, F jS \a\t g:i a')); ?>
			</div>
			
			<div id="ticket_options">
				<div class="btn-group">
					<?php if($ticket_info['status'] == "Pending" || $ticket_info['status'] == "Active") { ?>
					<a href="#replyModal" role="button" class="btn" data-toggle="modal">Reply</a>
					<a href="#closeModal" role="button" class="btn" data-toggle="modal">Close</a>
					<?php } ?>
					<a href="#updateModal" role="button" class="btn" data-toggle="modal">Edit</a>
				</div>
			</div>
			<!-- 
				######
				MODALS
				######

				Reply Modal
			-->
			<div id="replyModal" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
				<div class="modal-body">
					<h4>Reply to Ticket</h4>
					<div class="form-horizontal">
						<div class="control-group">
							<label class="control-label">Your Reply:</label>
							<div class="controls">
								<textarea name="message" rows="10" cols="75" id="message" placeholder="Type your reply here" maxlength="16777216"></textarea><br/>
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
					</div>
				</div>
				<div class="modal-footer" id="replyBtns">
					<button type="button" data-dismiss="modal" class="btn">Cancel</button>
					<input type="submit" onClick="addReply()" class="btn btn-primary" id="replybtn" value="Add Reply"/>
				</div>
				<div class="modal-footer" id="replyWait" style="display:none">
					<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
				</div>
			</div>
			<!--
				Close Modal
			-->
			<div id="closeModal" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
				<div class="modal-body">
					<h4>Close Ticket</h4>
					Are you sure you want to mark this issue as resolved? You can't reopen a closed ticket.
				</div>
				<div class="modal-footer" id="closeBtns">
					<button type="button" data-dismiss="modal" class="btn">Cancel</button>
					<input type="submit" onClick="closeTicket()" class="btn btn-primary" id="closebtn" value="Close Ticket"/>
				</div>
				<div class="modal-footer" id="closeWait" style="display:none">
					<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
				</div>
			</div>
			<!--
				Update Modal
			-->
			<div id="updateModal" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
				<div class="modal-body">
					<h4>Update Ticket Information</h4>
					<div class="form-horizontal">
						<div class="control-group">
							<label class="control-label">Assign to:</label>
							<div class="controls">
								<select name="assignmentSelection" id="assignmentSelection"></select>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Ticket Status:</label>
							<div class="controls">
								<label class="radio">
									<input type="radio" name="statusSelection" id="statusSelection1" value="Pending" <?php if($ticket_info['status'] == "Pending"){ echo('checked'); } ?>>
									<span class="label">Pending</span>
								</label>
								<label class="radio">
									<input type="radio" name="statusSelection" id="statusSelection1" value="Active" <?php if($ticket_info['status'] == "Active"){ echo('checked'); } ?>>
									<span class="label label-success">Active</span>
								</label>
								<label class="radio">
									<input type="radio" name="statusSelection" id="statusSelection1" value="Closed" <?php if($ticket_info['status'] == "Closed"){ echo('checked'); } ?>>
									<span class="label label-inverse">Closed</span>
								</label>
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<a href="del_ticket.php?id=<?php echo($ticket_info['id']); ?>">Delete Ticket</a>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" id="updateBtns">
					<button type="button" data-dismiss="modal" class="btn">Cancel</button>
					<input type="submit" onClick="updateTicket()" class="btn btn-primary" id="updatebtn" value="Save Changes"/>
				</div>
				<div class="modal-footer" id="updateWait" style="display:none">
					<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
				</div>
			</div>
			<!--
				Edit Message Modal
			-->
			<div id="editMessageModal" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
				<div class="modal-body">
					<h4>Edit Message</h4>
					<div class="form-horizontal">
						<div class="control-group">
							<label class="control-label">Your Reply:</label>
							<div class="controls">
								<textarea name="editReplyMessage" rows="10" cols="75" id="editReplyMessage" placeholder="Type your reply here" maxlength="16777216"></textarea><br/>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer" id="editMessageBtns">
					<button type="button" data-dismiss="modal" class="btn">Cancel</button>
					<input type="submit" class="btn btn-primary" id="editreplybtn" value="Save Changes"/>
				</div>
				<div class="modal-footer" id="editMessageWait" style="display:none">
					<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
				</div>
			</div>
		</div>
		<div class="span9">
			<h4>Ticket Updates</h4>
			<div id="ticketMessages">
<?php
	foreach($ticket_messages as $message){
		// If the message was from the Client.
		if($message['From'] == "Client"){

			// If the message is the word CLOSED, the ticket has been closed.
			if($message['Message'] == "CLOSED") { ?>
				<div class="alert alert-warning"><a onclick="deleteMessage(<?php echo($message['UpdateID']); ?>)" class="close"><i class="icon-remove"></i></a><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($message['Name']); ?> closed this ticket.</strong></div>
			<?php }

			// The message was not CLOSED, writing the message and screenshot (if any)
			else { ?>
				<div class="alert alert-notice"><a onclick="deleteMessage(<?php echo($message['UpdateID']); ?>)" class="close"><i class="icon-remove"></i></a>
					<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> <?php echo($message['Name']); ?> said:</strong><br/><?php echo($message['Message']); ?>
					<?php if($message['File'] != ""){ ?>
					<hr/>
					<div class="row-fluid"><ul><li class="span3">
						<a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" target="_blank" class="thumbnail">
							<img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" style="width: 260px" />
						</a>
					</li></ul></div>
					<?php } ?>
				</div>
			<?php } }


		// The message was from an agent.
		else { 
			// In the message if the word CLOSED, the ticket has been closed.
			if($message['Message'] == "CLOSED") { ?>
				<div class="alert alert-warning"><a onclick="deleteMessage(<?php echo($message['UpdateID']); ?>)" class="close"><i class="icon-remove"></i></a><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> you closed this ticket.</strong></div>
			<?php }

			// The message was not CLOSED, writing the message and screenshot (if any)
			else { ?>
				<div class="alert alert-success"><a onclick="showMessageModal(<?php echo($message['UpdateID']); ?>)" class="close"><i class="icon-edit"></i></a> <a onclick="deleteMessage(<?php echo($message['UpdateID']); ?>)" class="close"><i class="icon-remove"></i></a>
					<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> you said:</strong><br/><span id="reply_ID_<?php echo($message['UpdateID']); ?>"><?php echo($message['Message']); ?></span>
					<?php if($message['File'] != ""){ ?>
					<hr/><a href="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" class="msgimg" target="blank"><img src="http://i.imgur.com/<?php echo($message['File']); ?>.jpg" alt="User provided screenshot."/></a>
					<?php } ?>
				</div>
			<?php }
		}
	} ?>
</div></div></div>
<?php getFooter(); ?>