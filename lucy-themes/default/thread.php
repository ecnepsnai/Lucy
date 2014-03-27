<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('thread Status'); ?>
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
getNav(0); ?>	
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
				<?php } else { ?>
				<a href="#replyModal" role="button" class="btn btn-default" data-toggle="modal">Reply</a>
				<a href="#closeModal" role="button" class="btn btn-default" data-toggle="modal">Close</a>
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
					<div class="media client">
						<a class="pull-left">
							<img class="media-object" src="http://www.gravatar.com/avatar/<?php echo(md5($message->from->email)); ?>?s=64&d=mm">
						</a>
						<div class="media-body">
							<h4 class="media-heading"><?php echo($message->from->name); ?>:</h4>
						  	<span id="reply_ID_<?php echo($message->id); ?>"><?php echo($message->body); ?></span>
						  	<?php if(!empty($message->image)){ ?>
						  		<br/>
								<a href="lucy-content/uploads/<?php echo($message->image); ?>" target="_blank">
									<img src="lucy-content/uploads/<?php echo($message->image); ?>" style="width:64px"/>
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
					<div class="media agent">
						<a class="pull-right">
							<img class="media-object" src="http://www.gravatar.com/avatar/<?php echo(md5($message->from->email)); ?>?s=64&d=mm">
						</a>
						<div class="media-body">
							<h4 class="media-heading"><?php echo($message->from->name); ?>:</h4>
						  	<span id="reply_ID_<?php echo($message->id); ?>"><?php echo($message->body); ?></span>
						  	<?php if(!empty($message->image)){ ?>
						  		<br/>
								<a href="lucy-content/uploads/<?php echo($message->image); ?>" target="_blank">
									<img src="lucy-content/uploads/<?php echo($message->image); ?>" style="width:64px"/>
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
<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Reply to thread</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-2 control-label">Your Reply:</label>
						<div class="col-sm-10">
							<textarea name="message" class="form-control" rows="10" cols="75" id="message" placeholder="Type your reply here" maxlength="16777216"></textarea>
						</div>
					</div>
					<?php if($GLOBALS['config']['Images']['Enable']){ ?>
					<div class="form-group">
						<label class="col-sm-2 control-label">Attach Image:</label>
						<div class="col-sm-10">
							<input type="file" name="screenshot" id="screenshot" />
						</div>
					</div>
					<?php } ?>
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
	</div>
</div>

<!-- Close Modal -->
<div class="modal fade" id="closeModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Close thread</h4>
				</div>
			<div class="modal-body">
				<p>Are you sure you want to mark this issue as resolved? You can reopen it later.</p>
			</div>
			<div class="modal-footer" id="closeBtns">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<input type="submit" onClick="closethread()" class="btn btn-primary" id="closebtn" value="Close thread"/>
			</div>
			<div class="modal-footer" id="closeWait" style="display:none">
				<img src="lucy-themes/default/assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
			</div>
		</div>
	</div>
</div>

<!-- Reopen Modal -->
<div class="modal fade" id="openModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Reopen thread</h4>
				</div>
			<div class="modal-body">
				<p>Are you sure you want to reopen this thread?</p>
			</div>
			<div class="modal-footer" id="openBtns">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<input type="submit" onClick="openthread()" class="btn btn-primary" id="openbtn" value="Reopen thread"/>
			</div>
			<div class="modal-footer" id="openWait" style="display:none">
				<img src="lucy-themes/default/assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function closethread() {
		$("#closeBtns").hide();
		$("#closeWait").show();
		var postRequest = $.post("lucy-admin/api/thread_close.php", {
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
		var postRequest = $.post("lucy-admin/api/thread_reopen.php", {
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
		if (screenshots !== 0) {
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
		postRequest = $.post("lucy-admin/api/thread_update.php", {
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
</script>