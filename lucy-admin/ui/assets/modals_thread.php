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
				<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
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
				<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
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
				<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
			</div>
		</div>
	</div>
</div>

<!-- Spam Modal -->
<div class="modal fade" id="spamModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Flag as Spam</h4>
			</div>
			<div class="modal-body">
				<p>Are you sure you want to flag this thread as spam?</p>
			</div>
			<div class="modal-footer" id="flagBtns">
				<button type="button" data-dismiss="modal" class="btn">Cancel</button>
				<input type="submit" onClick="flagthread()" class="btn btn-primary" id="flagbtn" value="Flag thread"/>
			</div>
			<div class="modal-footer" id="flagWait" style="display:none">
				<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
			</div>
		</div>
	</div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update thread Information</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-3 control-label">Assign to:</label>
						<div class="col-sm-8">
							<select name="assignmentSelection" class="form-control" id="assignmentSelection"></select>
						</div>
					</div>
				</div>
				<div class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-3 control-label">Thread Status:</label>
						<div class="col-sm-8">
							<label class="radio">
								<input type="radio" name="statusSelection" id="statusSelection1" value="Pending" <?php if($thread_info['status'] == "Pending"){ echo('checked'); } ?>>
								<span class="label label-info">Pending</span>
							</label>
							<label class="radio">
								<input type="radio" name="statusSelection" id="statusSelection1" value="Active" <?php if($thread_info['status'] == "Active"){ echo('checked'); } ?>>
								<span class="label label-success">Active</span>
							</label>
							<label class="radio">
								<input type="radio" name="statusSelection" id="statusSelection1" value="Closed" <?php if($thread_info['status'] == "Closed"){ echo('checked'); } ?>>
								<span class="label label-default">Closed</span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer" id="updateBtns">
				<a class="btn btn-danger" href="del_thread.php?id=<?php echo($thread_info['id']); ?>">Delete thread</a>
				<button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
				<input type="submit" onClick="updatethread()" class="btn btn-primary" id="updatebtn" value="Save Changes"/>
			</div>
			<div class="modal-footer" id="updateWait" style="display:none">
				<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
			</div>
		</div>
	</div>
</div>

<!-- Edit Message Modal -->
<div id="editMessageModal" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-body">
		<h4 class="modal-title">Edit Message</h4>
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

<!-- Delete Message Modal -->
<div id="delMessageModal" class="modal hide fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-body">
		<h4 class="modal-title">Delete Message</h4>
		Are you sure you want to delete this message?
	</div>
	<div class="modal-footer" id="delBtns">
		<button type="button" data-dismiss="modal" class="btn">Cancel</button>
		<input type="submit" class="btn btn-primary" id="delbtn" value="Delete Message"/>
	</div>
	<div class="modal-footer" id="delWait" style="display:none">
		<img src="assets/img/ajax-loader.gif" style="width:16px;height=16px;" alt="Please Wait" /> Please Wait...
	</div>
</div>