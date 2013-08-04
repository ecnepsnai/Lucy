<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Ticket Status'); ?>
<link href="lucy-themes/default/assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script src="lucy-themes/default/assets/js/bootstrap-fileupload.js"></script>
<link href="lucy-themes/default/assets/css/bootstrap-modal.css" rel="stylesheet">
<script src="lucy-themes/default/assets/js/bootstrap-modal.js"></script>
<script src="lucy-themes/default/assets/js/bootstrap-modalmanager.js"></script>
<?php
getNav(0); ?>	
<script type="text/javascript">
	function hideTools() {
		$('#ticket_options').hide();
		$('#ticket_reply').show();
	}
	function closeTicket() {
		$("#closeBtns").hide();
		$("#closeWait").show();
		var postRequest = $.post("lucy-admin/api/ticket_close.php", {
			ownerID: "<?php echo($ticket_info['owner']); ?>", 
			id: "<?php echo($ticket_info['id']); ?>"
		});

		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#closeBtns").show();
				$("#closeWait").hide();
			} else {
				window.location.reload();
			}
		});
	}
	function addReply() {
		$("#replyBtns").hide();
		$("#replyWait").show();

		var reader = new FileReader();
		reader.readAsBinaryString(document.getElementsByName("screenshot")[1].files[0]);
		var data = null;
		reader.onload = function(e) {
			data = btoa(e.currentTarget.result);
			console.log(data);
		}

		var postRequest = $.post("lucy-admin/api/ticket_update.php", {
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
				$('#ticketMessages').append('<div class="alert alert-notice"><strong>On <?php echo(date('l, F jS \a\t g:i a')); ?> you said:</strong><br>' + $("#message").val() + '</div>');
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
			<?php if($ticket_info['status'] == "Pending" || $ticket_info['status'] == "Active") { ?>
			<div id="ticket_options">
				<a href="#replyModal" role="button" class="btn" data-toggle="modal" id="replybtn">Add Reply</a> <a href="#closeModal" role="button" class="btn" data-toggle="modal" id="closebtn">Close Ticket</a>
			</div>
			<div id="replyModal" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
				<div class="modal-body">
					<h4>Reply to Ticket</h4>
					<div class="form-horizontal">
						<input type="hidden" name="reply" value="ReplyToTicket"/>
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
					<img src="lucy-themes/default/assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
				</div>
			</div>
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
					<img src="lucy-themes/default/assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
				</div>
			</div>
			<?php } ?>
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
				<div class="alert alert-warning"><strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> you closed this ticket.</strong></div>
			<?php }

			// The message was not CLOSED, writing the message and screenshot (if any)
			else { ?>
				<div class="alert alert-notice">
					<strong>On <?php echo(date_format(date_create($message['Date']), 'l, F jS \a\t g:i a')); ?> you said:</strong><br/><?php echo($message['Message']); ?>
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
		elseif($message['From'] == "Agent"){
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
				</div>
			<?php }
		}

		// The message was from a bot.
		elseif($message['From'] == "Bot"){
			//Currently the only active bot is the spam bot, it posts a message in all caps saying "SPAM"
			if($message['Message'] == "SPAM") { ?>
				<div class="alert alert-error"><strong>This ticket has been flagged as spam and automatically closed.</strong></div>
			<?php }
		}
	} ?> </div> <?php


// If the ticket is open, we display the ticket options.
	if($ticket_info['status'] == "Pending" || $ticket_info['status'] == "Active") { ?>
<div id="ticketOptions">
	<div id="ticket_reply" style="display:none">
		<hr/>
		<h2>Ticket Options</h2>
		<div class="form-horizontal">
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
			<input type="submit" class="btn btn-primary" id="blue" onClick="addReply()" value="Add Reply"/>
		</div>
	</div>
</div>
<?php } ?></div></div>
<?php getFooter(); ?>