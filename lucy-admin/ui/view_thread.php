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
	$id = $id;


	try {
		$response = $cda->select(null,"threads",array("id"=>$id));
	} catch (Exception $e) {
		die($e);
	}
	$thread_info = $response['data'];

	// If no data was returned -- no thread does not exist.
	if(count($thread_info) == 0){
		die();
	}

	$thread_messages = json_decode($thread_info['data']);

	// THIS SHOULD NOT RETURN TRUE
	// If the thread table has no data in it.
	if(count($thread_messages) == 0){
		die("No thread Information");
	}


	// Mailer operations
	if(isset($_POST['close']) && $_POST['close'] == "Closethread"){
		// Sends update email.
		//mailer_threadUpdate($thread_info['name'], $thread_info['email'], $thread_info['id']);
	}
	if(isset($_POST['reply']) && $_POST['reply'] == "ReplyTothread"){
		// Sends update email.
		//mailer_threadUpdate($thread_info['name'], $thread_info['email'], $thread_info['id']);
	}

	getHeader("View thread");
?>
<style type="text/css">
.media.agent{
	padding: 1em;
	background-color: #EFF7EC;
}
.media.client{
	padding: 1em;
	background-color: #f7f7f9;
}
.media{
	border-radius: 5px;
}
.alert{
	margin-top: 1em;
}
</style>
<?php
	getNav(2);
?>
<div class="row">
	<div class="col-md-4">
		<h4>Thread Info</h4>
		<div class="alert alert-info">
			<?php foreach($thread_messages->values as $variable => $value){ ?>
			<strong><?php echo(ucfirst($variable)); ?>:</strong> <?php echo($value); ?><br/>
			<?php } ?>
			<strong>Status:</strong> <?php echo($thread_info['status']); ?><br/>
			<strong>Created:</strong> <?php echo(date_format(date_create($thread_info['date']), 'd/m/Y \a\t g:i a')); ?>
		</div>
		
		<div id="thread_options">
			<div class="btn-group">
				<?php if($thread_info['status'] == "Closed") { ?>
				<a href="#openModal" role="button" class="btn btn-default" data-toggle="modal" id="openbtn">Reopen Thread</a>
				<a href="#updateModal" role="button" class="btn btn-default" data-toggle="modal">Edit</a>
				<?php } else { ?>
				<a href="#replyModal" role="button" class="btn btn-default" data-toggle="modal">Reply</a>
				<a href="#closeModal" role="button" class="btn btn-default" data-toggle="modal">Close</a>
				<a href="#spamModal" role="button" class="btn btn-default" data-toggle="modal">Spam</a>
				<a href="#updateModal" role="button" class="btn btn-default" data-toggle="modal">Edit</a>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-8">
		<h4>Thread Updates</h4>
		<div id="threadMessages">
<?php
	foreach($thread_messages->messages as $message){
		// If the message was from OP
		if($message->from->id == $thread_info['owner']){
			switch ($message->body) {
				case 'CLOSED': ?>
					<div class="alert alert-warning"><strong>Thread closed by <?php echo($message->from->name); ?></strong></div>
				<?php
				break;

				case 'OPEN': ?>
					<div class="alert alert-success"><strong>Thread reopened by <?php echo($message->from->name); ?></strong></div>
				<?php
				break;
				
				default: ?>
					<div class="media agent">
						<a class="pull-left">
							<img class="media-object" src="http://www.gravatar.com/avatar/<?php echo(md5($message->from->email)); ?>?s=64&d=mm">
						</a>
						<div class="media-body">
							<h4 class="media-heading"><?php echo($message->from->name); ?>:</h4>
						  	<span id="reply_ID_<?php echo($message->id); ?>"><?php echo($message->body); ?></span>
						  	<?php if(!empty($message->image)){ ?>
						  		<br/>
								<a href="../../lucy-content/uploads/<?php echo($message->image); ?>" target="_blank">
									<img src="../../lucy-content/uploads/<?php echo($message->image); ?>" style="width:64px"/>
								</a>
							<?php } ?>
						</div>
					</div>
				<?php
				break;
			}
		}

		// The message is from somebody else
		else {
			switch ($message->body) {
				case 'CLOSED': ?>
					<div class="alert alert-warning"><strong>Thread closed by <?php echo($message->from->name); ?></strong></div>
				<?php
				break;

				case 'OPEN': ?>
					<div class="alert alert-success"><strong>Thread reopened by <?php echo($message->from->name); ?></strong></div>
				<?php
				break;

				case 'SPAM': ?>
					<div class="alert alert-error"><strong>Thread marked as spam.</strong></div>
				<?php
				break;
				
				default: ?>
					<div class="media client">
						<a class="pull-right">
							<img class="media-object" src="http://www.gravatar.com/avatar/<?php echo(md5($message->from->email)); ?>?s=64&d=mm">
						</a>
						<div class="media-body">
							<h4 class="media-heading"><?php echo($message->from->name); ?>:</h4>
						  	<span id="reply_ID_<?php echo($message->id); ?>"><?php echo($message->body); ?></span>
						  	<?php if(!empty($message->image)){ ?>
						  		<br/>
								<a href="../../lucy-content/uploads/<?php echo($message->image); ?>" target="_blank">
									<img src="../../lucy-content/uploads/<?php echo($message->image); ?>" style="width:64px"/>
								</a>
							<?php } ?>
						</div>
					</div>
				<?php
				break;
			}
		}
	}

?>
</div>
</div>
</div>
<?php getFooter(); ?>

<?php require('assets/modals_thread.php'); ?>

<script type="text/javascript">

	var postRequest = $.post("../api/admin_get_users.php");

	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			alert(obj.response.message);
		} else {
			var length = obj.response.data.users.length;
			var isassignedto = <?php if(!empty($thread_info['assignedto'])){ echo($thread_info['assignedto']);} else { echo("null"); } ?>;
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

	function closethread() {
		$("#closeBtns").hide();
		$("#closeWait").show();
		var postRequest = $.post("../api/thread_close.php", {
			ownerID: "<?php echo($thread_info['owner']); ?>", 
			id: "<?php echo($thread_info['id']); ?>"
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
	function openthread() {
		$("#openBtns").hide();
		$("#openWait").show();
		var postRequest = $.post("../api/thread_reopen.php", {
			ownerID: "<?php echo($thread_info['owner']); ?>", 
			id: "<?php echo($thread_info['id']); ?>"
		});

		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#openBtns").show();
				$("#openWait").hide();
			} else {
				window.location.reload();
			}
		});
	}
	function flagthread() {
		$("#flagBtns").hide();
		$("#flagWait").show();
		var postRequest = $.post("../api/admin_flag_spam.php", {
			id: "<?php echo($thread_info['id']); ?>"
		});

		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#flagBtns").show();
				$("#flagWait").hide();
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

		var imageData = null;
		var postRequest = null;
		var screenshots = null;
		try{
			screenshots = document.getElementById("screenshot").files.length;
		} catch(err) {
			//
		}
		if (screenshots != null) {
			oFReader = new FileReader(), rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
			oFReader.addEventListener("load",function(event){
				var imageFile = event.target;
				imageData = imageFile.result;
				sendUpdatePost("<?php echo($thread_info['owner']); ?>", "<?php echo($thread_info['id']); ?>", $("#message").val(), imageData);
			});
			var oFile = document.getElementById("screenshot").files[0];
			if (!rFilter.test(oFile.type)) { alert("You must select a valid image file!"); return; }
			oFReader.readAsDataURL(oFile);
		} else {
			sendUpdatePost("<?php echo($thread_info['owner']); ?>", "<?php echo($thread_info['id']); ?>", $("#message").val(), null);
		}
	}
	function sendUpdatePost(ownerID,id,message,image){
		postRequest = $.post("../api/thread_update.php", {
			ownerID: ownerID, 
			id: id,
			message: message,
			image: image
		});
		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			console.log(obj);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#replyBtns").show();
				$("#replyWait").hide();
			} else {
				$('#replyModal').modal('hide');
				window.location.reload();
			}
		});
	}
	function updatethread() {
		$("#updateBtns").hide();
		$("#updateWait").show();
		var postRequest = $.post("../api/admin_edit_thread.php", {
			assignment: document.getElementsByName("assignmentSelection")[0].value,
			status: $('input[name="statusSelection"]:checked').val(),
			id: "<?php echo($thread_info['id']); ?>"
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
			threadid: "<?php echo($thread_info['id']); ?>",
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
	function showdelMessageModal(messageID) {
		$("#delbtn").attr('onclick','delMessage(' + messageID + ')');
		$('#delMessageModal').modal('show');
	}
	function delMessage(messageID) {
		$("#delMessageBtns").hide();
		$("#delMessageWait").show();
		var postRequest = $.post("../api/admin_del_message.php", {
			threadid: "<?php echo($thread_info['id']); ?>",
			updateid: messageID
		});

		postRequest.done(function(data){
			var obj = jQuery.parseJSON(data);
			if(obj.response.code != 200){
				alert(obj.response.message);
				$("#delMessageBtns").show();
				$("#delMessageWait").hide();
			} else {
				window.location.reload();
			}
		});
	}
</script>