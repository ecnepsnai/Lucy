<?php
	require("../session.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	getHeader("Form Designer"); ?>
<style type="text/css">
.input_object{
	padding: 1em;
	border-radius: 5px;
	background-color: #f7f7f9;
	margin-top: 15px;
}
.input_object:first-child {
	margin-top: 0;
}
.row {
	padding-top: 1em;
}
#inputOrder{
	padding: 0;
	margin: 0;
	list-style: none;
}
#inputOrder li{
	border: 1px solid #ccc;
	border-radius: 2px;
	padding: 5px;
	background: #eee;
	margin-bottom: 5px;
	cursor: pointer;
	font-weight: bold;
}
.modal-body ol li:hover{
	text-decoration: line-through;
	color: #999;
	cursor: pointer;
}
</style>
	<?php getNav(5);
?>
<h1>Form Designer</h1>
<div class="row">
	<div class="col-md-2">
		<div class="btn-group">
			<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
				Add Input <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="#" id="textLink">Text Box</a></li>
				<li><a href="#" id="numberLink">Number Box</a></li>
				<li><a href="#" id="rangeLink">Range Box</a></li>
				<li><a href="#" id="selectLink">Dropdown Box</a></li>
				<li><a href="#" id="textareaLink">Text Area</a></li>
				<li><a href="#" id="checkboxLink">Check Box</a></li>
				<li><a href="#" id="radioLink">Radio Button</a></li>
			</ul>
		</div>
		<div class="btn-group">
			<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
				<span class="glyphicon glyphicon-cog"></span> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="#" onclick="return showPreview()" target="_blank">Preview</a></li>
				<li><a href="#presetModal" role="button" data-toggle="modal">Export / Import</a></li>
				<li class="divider"></li>
				<li><a href="//ianspence.com/support/docs/lucy/designer" target="_blank">Help</a></li>
			</ul>
		</div>
		<h3>Edit Order</h3>
		<div>
			<ul id="inputOrder">
				<?php foreach ($GLOBALS['config']['Support']['Order'] as $input_name) { echo('<li>' . $input_name . '</li>'); } ?>
			</ul>
			<input type="hidden" id="orderHidden" />
			<div id="orderControls" style="display:none">
				<a href="#" id="orderSave" class="btn btn-primary">Save</a>
			</div>
		</div>
	</div>
	<div class="col-md-10">
		<h3>Custom Inputs</h3>
		<div id="form_designer">
			<?php
if(count($designer['config']) == 0){
echo('<p class="muted">dust...</p>');
} else {
?>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Type</th>
						<th>Title</th>
						<th>Help Text</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
<?php 
}

$count = 1;

foreach (getInputs() as $input_name => $input) { ?>
	<tr><td><?php echo($count); ?></td><td><?php echo($input_name); ?></td><td><?php echo($input['type']); ?></td><td><?php echo($input['title']); ?></td><td><?php echo($input['helptext']); ?><td><a class="btn btn-default btn-xs" href="#" id="edit_<?php echo($input_name); ?>"><span class="glyphicon glyphicon-edit"></span></a> <a class="btn btn-default btn-xs" href="#" id="rm_<?php echo($input_name); ?>"><span class="glyphicon glyphicon-remove"></span></a></td></tr>
<?php $count++; }
?>
				</tbody>
			</table>
		</div>
		<h3>Required Inputs</h3>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Type</th>
					<th>Title</th>
					<th>Help Text</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td>name</td>
					<td>text</td>
					<td><?php echo($designer['static']['name']['title']); ?></td>
					<td><?php echo($designer['static']['name']['helptext']); ?></td>
					<td><a class="btn btn-default btn-xs" href="#" id="edit_name"><span class="glyphicon glyphicon-edit"></span></a></td>
				</tr>
				<tr>
					<td>2</td>
					<td>email</td>
					<td>text</td>
					<td><?php echo($designer['static']['email']['title']); ?></td>
					<td><?php echo($designer['static']['email']['helptext']); ?></td>
					<td><a class="btn btn-default btn-xs" href="#" id="edit_email"><span class="glyphicon glyphicon-edit"></span></a></td>
				</tr>
				<tr>
					<td>3</td>
					<td>password</td>
					<td>secure</td>
					<td><?php echo($designer['static']['password']['title']); ?></td>
					<td><?php echo($designer['static']['password']['helptext']); ?></td>
					<td><a class="btn btn-default btn-xs" href="#" id="edit_password"><span class="glyphicon glyphicon-edit"></span></a></td>
				</tr>
				<tr>
					<td>4</td>
					<td>message</td>
					<td>textarea</td>
					<td><?php echo($designer['static']['message']['title']); ?></td>
					<td><?php echo($designer['static']['message']['helptext']); ?></td>
					<td><a class="btn btn-default btn-xs" href="#" id="edit_message"><span class="glyphicon glyphicon-edit"></span></a></td>
				</tr>
				<tr>
					<td>5</td>
					<td>image</td>
					<td>file</td>
					<td><?php echo($designer['static']['image']['title']); ?></td>
					<td><?php echo($designer['static']['image']['helptext']); ?></td>
					<td><a class="btn btn-default btn-xs" href="#" id="edit_image"><span class="glyphicon glyphicon-edit"></span></a></td>
				</tr>
			</tbody>
		</table>
		<em>The Name, Email, and Password inputs are not shown to users who are signed in</em>
	</div>
</div>
<?php getFooter(); ?>

<?php require('assets/modals_designer.php'); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript">

<?php foreach ($designer['config'] as $input_name => $input) { ?>
$("#edit_<?php echo($input_name); ?>").bind('click',function(){
	$("#edit<?php echo($input['type']); ?>Modal #editSaveBtn").unbind();
	$("#edit<?php echo($input['type']); ?>Modal #title").val('<?php echo($input['title']); ?>');
	$("#edit<?php echo($input['type']); ?>Modal #help").val('<?php echo($input['helptext']); ?>');
	$("#edit<?php echo($input['type']); ?>Modal #editSaveBtn").bind('click',function(){
		editInput('<?php echo($input['type']); ?>','<?php echo($input_name); ?>');
	});
	<?php
	if($input['required']){ ?>$("#edit<?php echo($input['type']); ?>Modal #req").attr('checked','checked');<?php }
	switch ($input['type']) {
		case 'text': ?>
			$("#edittextModal #length_min").val('<?php echo($input['length_min']); ?>');
			$("#edittextModal #length_max").val('<?php echo($input['length_max']); ?>');
			<?php if($input['acpt_num']){ ?>$("#edittextModal #acpt_num").attr('checked','checked');<?php } ?>
			<?php if($input['acpt_sym']){ ?>$("#edittextModal #acpt_sym").attr('checked','checked');<?php } ?>
		<?php break;
		case 'number': ?>
			$("#editnumberModal #length_min").val('<?php echo($input['length_min']); ?>');
			$("#editnumberModal #length_max").val('<?php echo($input['length_max']); ?>');
		<?php break;
		case 'select': ?>
			$("#editselectModal #dropdownOptions").val(',<?php echo($input['options']); ?>');
			"<?php echo($input['options']); ?>".split(',').forEach(function(e){
				var li = document.createElement('li');
				$(li).text(e);
				$(li).bind('click',function(){
					$("#editselectModal #dropdownOptions").val($("#editselectModal #dropdownOptions").val().replace(',' + $(this).text(),''));
					$(this).remove();
				});
				$("#editselectModal #dropdownItems").append(li);
			});
			$("#editselectModal #addDropbownItem").bind("click", function(){
				var option = prompt("Enter dropdown selection:");
				if(option != "" || option != null){
					var li = document.createElement('li');
					$(li).text(option);
					$(li).bind('click',function(){
						$("#editselectModal #dropdownOptions").val($("#editselectModal #dropdownOptions").val().replace(',' + $(this).text(),''));
						$(this).remove();
					});
					$("#editselectModal #dropdownItems").append(li);
					var value = $("#editselectModal #dropdownOptions").val();
					$("#editselectModal #dropdownOptions").val(value + ',' + option);
				}
			});
		<?php break;
		case 'textarea': ?>
	
		<?php break;
		case 'checkbox': ?>
			$("#editcheckboxModal #checkboxOptions").val(",<?php echo($input['options']); ?>");
			"<?php echo($input['options']); ?>".split(',').forEach(function(e){
				var li = document.createElement('li');
				$(li).text(e);
				$(li).bind('click',function(){
					$("#editcheckboxModal #checkboxOptions").val($("#editcheckboxModal #checkboxOptions").val().replace(',' + $(this).text(),''));
					$(this).remove();
				});
				$("#editcheckboxModal #checkboxItems").append(li);
			});
			$("#editcheckboxModal #addCheckboxItem").bind("click", function(){
				var option = prompt("Enter checkbox selection:");
				if(option != "" || option != null){
					var li = document.createElement('li');
					$(li).text(option);
					$(li).bind('click',function(){
						$("#editcheckboxModal #checkboxOptions").val($("#editcheckboxModal #checkboxOptions").val().replace(',' + $(this).text(),''));
						$(this).remove();
					});
					$("#editcheckboxModal #dropdownItems").append(li);
					var value = $("#editcheckboxModal #checkboxOptions").val();
					$("#editcheckboxModal #checkboxOptions").val(value + ',' + option);
				}
			});
		<?php break;
		case 'radio': ?>
			$("#editradioModal #radioOptions").val(",<?php echo($input['options']); ?>");
			"<?php echo($input['options']); ?>".split(',').forEach(function(e){
				var li = document.createElement('li');
				$(li).text(e);
				$(li).bind('click',function(){
					$("#editradioModal #radioOptions").val($("#editradioModal #radioOptions").val().replace(',' + $(this).text(),''));
					$(this).remove();
				});
				$("#editradioModal #radioItems").append(li);
			});
			$("#editradioModal #addRadioItem").bind("click", function(){
				var option = prompt("Enter radio selection:");
				if(option != "" || option != null){
					var li = document.createElement('li');
					$(li).text(option);
					$(li).bind('click',function(){
						$("#editradioModal #radioOptions").val($("#editradioModal #radioOptions").val().replace(',' + $(this).text(),''));
						$(this).remove();
					});
					$("#editradioModal #radioItems").append(li);
					var value = $("#editradioModal #radioOptions").val();
					$("#editradioModal #radioOptions").val(value + ',' + option);
				}
			});
		<?php break;
	}
	?>
	$("#edit<?php echo($input['type']); ?>Modal").modal("show");
});
$("#rm_<?php echo($input_name); ?>").bind('click',function(){
	$("#deleteModal #delinputBtn").unbind();
	$("#deleteModal #delinputBtn").bind('click',function(){
		removeInput('<?php echo($input_name); ?>');
	});
	$("#deleteModal").modal("show");
});
<?php } ?>

$("#textLink").bind("click", function() {
	$("#textModal").modal("show");
});
$("#numberLink").bind("click", function() {
	$("#numberModal").modal("show");
});
$("#rangeLink").bind("click", function() {
	$("#rangeModal").modal("show");
});
$("#selectLink").bind("click", function() {
	$("#addDropbownItem").bind("click", function(){
		var option = prompt("Enter dropdown selection:");
		if(option != "" || option != null){
			var li = document.createElement('li');
			$(li).text(option);
			$(li).bind('click',function(){
				$("#dropdownOptions").val($("#dropdownOptions").val().replace(',' + $(this).text(),''));
				$(this).remove();
			});
			$("#dropdownItems").append(li);
			var value = $("#dropdownOptions").val();
			$("#dropdownOptions").val(value + ',' + option);
		}
	});
	$("#selectModal").modal("show");
});
$("#textareaLink").bind("click", function() {
	$("#textareaModal").modal("show");
});
$("#checkboxLink").bind("click", function() {
	$("#addCheckboxItem").bind("click", function(){
		var option = prompt("Enter checkbox choice:");
		if(option != "" || option != null){
			var li = document.createElement('li');
			$(li).text(option);
			$(li).bind('click',function(){
				$("#checkboxOptions").val($("#checkboxOptions").val().replace(',' + $(this).text(),''));
				$(this).remove();
			});
			$("#checkboxItems").append(li);
			var value = $("#checkboxOptions").val();
			$("#checkboxOptions").val(value + ',' + option);
		}
	});
	$("#checkboxModal").modal("show");
});
$("#radioLink").bind("click", function() {
	$("#addRadioItem").bind("click", function(){
		var option = prompt("Enter radio choice:");
		if(option != "" || option != null){
			var li = document.createElement('li');
			$(li).text(option);
			$(li).bind('click',function(){
				$("#radioOptions").val($("#radioOptions").val().replace(',' + $(this).text(),''));
				$(this).remove();
			});
			$("#radioItems").append(li);
			var value = $("#radioOptions").val();
			$("#radioOptions").val(value + ',' + option);
		}
	});
	$("#radioModal").modal("show");
});
$("#edit_name").bind("click", function() {
	$("#nameModal #title").val("<?php echo($designer['static']['name']['title']); ?>");
	$("#nameModal #help").val("<?php echo($designer['static']['name']['helptext']); ?>");
	$("#nameModal").modal("show");
});
$("#edit_email").bind("click", function() {
	$("#emailModal #title").val("<?php echo($designer['static']['email']['title']); ?>");
	$("#emailModal #help").val("<?php echo($designer['static']['email']['helptext']); ?>");
	$("#emailModal").modal("show");
});
$("#edit_password").bind("click", function() {
	$("#passwordModal #title").val("<?php echo($designer['static']['password']['title']); ?>");
	$("#passwordModal #help").val("<?php echo($designer['static']['password']['helptext']); ?>");
	$("#passwordModal").modal("show");
});
$("#edit_message").bind("click", function() {
	$("#messageModal #title").val("<?php echo($designer['static']['message']['title']); ?>");
	$("#messageModal #help").val("<?php echo($designer['static']['message']['helptext']); ?>");
	$("#messageModal").modal("show");
});
$("#edit_image").bind("click", function() {
	$("#imageModal #title").val("<?php echo($designer['static']['image']['title']); ?>");
	$("#imageModal #help").val("<?php echo($designer['static']['image']['helptext']); ?>");
	$("#imageModal").modal("show");
});
function addInput(inputType){
	switch(inputType){
		case 'text':
			var name = $("#textModal #name").val();
			var title = $("#textModal #title").val();
			var helptext = $("#textModal #help").val();
			var required = false;
			if($("#textModal #req").is(':checked')){
				required = true;
			}
			var length_min = $("#textModal #length_min").val();
			var length_max = $("#textModal #length_max").val();
			var acpt_num = false;
			if($("#textModal #acpt_num").is(':checked')){
				acpt_num = true;
			}
			var acpt_sym = false;
			if($("#textModal #acpt_sym").is(':checked')){
				acpt_sym = true;
			}
			postRequest = $.post("../api/admin_designer_add.php", {
				name: name,
				type: 'text',
				title: title,
				helptext: helptext,
				required: required,
				length_min: length_min,
				length_max: length_max,
				acpt_num: acpt_num,
				acpt_sym: acpt_sym
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'number':
			var name = $("#numberModal #name").val();
			var title = $("#numberModal #title").val();
			var helptext = $("#numberModal #help").val();
			var required = false;
			if($("#numberModal #req").is(':checked')){
				required = true;
			}
			var length_min = $("#numberModal #length_min").val();
			var length_max = $("#numberModal #length_max").val();
			postRequest = $.post("../api/admin_designer_add.php", {
				name: name,
				type: 'number',
				title: title,
				helptext: helptext,
				required: required,
				length_min: length_min,
				length_max: length_max,
				acpt_num: acpt_num,
				acpt_sym: acpt_sym
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'range':
			var name = $("#rangeModal #name").val();
			var title = $("#rangeModal #title").val();
			var helptext = $("#rangeModal #help").val();
			var required = false;
			if($("#rangeModal #req").is(':checked')){
				required = true;
			}
			var length_min = $("#rangeModal #length_min").val();
			var length_max = $("#rangeModal #length_max").val();
			postRequest = $.post("../api/admin_designer_add.php", {
				name: name,
				type: 'range',
				title: title,
				helptext: helptext,
				required: required,
				length_min: length_min,
				length_max: length_max,
				acpt_num: acpt_num,
				acpt_sym: acpt_sym
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'select':
			var name = $("#selectModal #name").val();
			var title = $("#selectModal #title").val();
			var helptext = $("#selectModal #help").val();
			var required = false;
			if($("#selectModal #req").is(':checked')){
				required = true;
			}
			var options = $("#selectModal #dropdownOptions").val();
			options = options.replace(",","");
			postRequest = $.post("../api/admin_designer_add.php", {
				name: name,
				type: 'select',
				title: title,
				helptext: helptext,
				required: required,
				options: options
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'textarea':
			var name = $("#textareaModal #name").val();
			var title = $("#textareaModal #title").val();
			var helptext = $("#textareaModal #help").val();
			var required = false;
			if($("#textareaModal #req").is(':checked')){
				required = true;
			}
			postRequest = $.post("../api/admin_designer_add.php", {
				name: name,
				type: 'textarea',
				title: title,
				helptext: helptext,
				required: required
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'checkbox':
			var name = $("#checkboxModal #name").val();
			var title = $("#checkboxModal #title").val();
			var helptext = $("#checkboxModal #help").val();
			var required = false;
			if($("#checkboxModal #req").is(':checked')){
				required = true;
			}
			var options = $("#checkboxModal #checkboxOptions").val();
			options = options.replace(",","");
			postRequest = $.post("../api/admin_designer_add.php", {
				name: name,
				type: 'checkbox',
				title: title,
				helptext: helptext,
				required: required,
				options: options
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'radio':
			var name = $("#radioModal #name").val();
			var title = $("#radioModal #title").val();
			var helptext = $("#radioModal #help").val();
			var required = false;
			if($("#radioModal #req").is(':checked')){
				required = true;
			}
			var options = $("#radioModal #radioOptions").val();
			options = options.replace(",","");
			postRequest = $.post("../api/admin_designer_add.php", {
				name: name,
				type: 'radio',
				title: title,
				helptext: helptext,
				required: required,
				options: options
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;
	}
}

function editInput(inputType, inputName){
	switch(inputType){
		case 'text':
			var name = $("#edittextModal #name").val();
			var title = $("#edittextModal #title").val();
			var helptext = $("#edittextModal #help").val();
			var required = false;
			if($("#edittextModal #req").is(':checked')){
				required = true;
			}
			var length_min = $("#edittextModal #length_min").val();
			var length_max = $("#edittextModal #length_max").val();
			var acpt_num = false;
			if($("#edittextModal #acpt_num").is(':checked')){
				acpt_num = true;
			}
			var acpt_sym = false;
			if($("#edittextModal #acpt_sym").is(':checked')){
				acpt_sym = true;
			}
			postRequest = $.post("../api/admin_designer_edit.php", {
				name: inputName,
				type: 'text',
				title: title,
				helptext: helptext,
				required: required,
				length_min: length_min,
				length_max: length_max,
				acpt_num: acpt_num,
				acpt_sym: acpt_sym
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'number':
			var name = $("#editnumberModal #name").val();
			var title = $("#editnumberModal #title").val();
			var helptext = $("#editnumberModal #help").val();
			var required = false;
			if($("#editnumberModal #req").is(':checked')){
				required = true;
			}
			var length_min = $("#editnumberModal #length_min").val();
			var length_max = $("#editnumberModal #length_max").val();
			postRequest = $.post("../api/admin_designer_edit.php", {
				name: inputName,
				type: 'number',
				title: title,
				helptext: helptext,
				required: required,
				length_min: length_min,
				length_max: length_max,
				acpt_num: acpt_num,
				acpt_sym: acpt_sym
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'select':
			var name = $("#editselectModal #name").val();
			var title = $("#editselectModal #title").val();
			var helptext = $("#editselectModal #help").val();
			var required = false;
			if($("#editselectModal #req").is(':checked')){
				required = true;
			}
			var options = $("#editselectModal #dropdownOptions").val();
			options = options.replace(",","");
			postRequest = $.post("../api/admin_designer_edit.php", {
				name: inputName,
				type: 'select',
				title: title,
				helptext: helptext,
				required: required,
				options: options
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'textarea':
			var name = $("#edittextareaModal #name").val();
			var title = $("#edittextareaModal #title").val();
			var helptext = $("#edittextareaModal #help").val();
			var required = false;
			if($("#edittextareaModal #req").is(':checked')){
				required = true;
			}
			postRequest = $.post("../api/admin_designer_edit.php", {
				name: inputName,
				type: 'textarea',
				title: title,
				helptext: helptext,
				required: required
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'checkbox':
			var name = $("#editcheckboxModal #name").val();
			var title = $("#editcheckboxModal #title").val();
			var helptext = $("#editcheckboxModal #help").val();
			var required = false;
			if($("#editcheckboxModal #req").is(':checked')){
				required = true;
			}
			var options = $("#editcheckboxModal #checkboxOptions").val();
			options = options.replace(",","");
			postRequest = $.post("../api/admin_designer_edit.php", {
				name: inputName,
				type: 'checkbox',
				title: title,
				helptext: helptext,
				required: required,
				options: options
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;

		case 'radio':
			var name = $("#editradioModal #name").val();
			var title = $("#editradioModal #title").val();
			var helptext = $("#editradioModal #help").val();
			var required = false;
			if($("#editradioModal #req").is(':checked')){
				required = true;
			}
			var options = $("#editradioModal #radioOptions").val();
			options = options.replace(",","");
			postRequest = $.post("../api/admin_designer_edit.php", {
				name: inputName,
				type: 'radio',
				title: title,
				helptext: helptext,
				required: required,
				options: options
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;
	}
}

function removeInput(inputName){
	postRequest = $.post("../api/admin_designer_remove.php", {
		name: inputName
	});
	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			alert(obj.response.message);
		} else {
			window.location.reload();
		}
	});
}

function updateConstant(constName){
	switch(constName){
		case 'name':
			var title = $("#nameModal #title").val();
			var helptext = $("#nameModal #help").val();
			postRequest = $.post("../api/admin_designer_edit_constant.php", {
				name: 'name',
				title: title,
				helptext: helptext
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;
		case 'email':
			var title = $("#emailModal #title").val();
			var helptext = $("#emailModal #help").val();
			postRequest = $.post("../api/admin_designer_edit_constant.php", {
				name: 'email',
				title: title,
				helptext: helptext
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;
		case 'password':
			var title = $("#passwordModal #title").val();
			var helptext = $("#passwordModal #help").val();
			postRequest = $.post("../api/admin_designer_edit_constant.php", {
				name: 'password',
				title: title,
				helptext: helptext
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;
		case 'message':
			var title = $("#messageModal #title").val();
			var helptext = $("#messageModal #help").val();
			postRequest = $.post("../api/admin_designer_edit_constant.php", {
				name: 'message',
				title: title,
				helptext: helptext
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;
		case 'image':
			var title = $("#imageModal #title").val();
			var helptext = $("#imageModal #help").val();
			postRequest = $.post("../api/admin_designer_edit_constant.php", {
				name: 'image',
				title: title,
				helptext: helptext
			});
			postRequest.done(function(data){
				var obj = jQuery.parseJSON(data);
				if(obj.response.code != 200){
					alert(obj.response.message);
				} else {
					window.location.reload();
				}
			});
		break;
	}
}

$("#inputOrder").sortable({
	update: function(event, ui){
		var data = "";
		$("#inputOrder li").each(function(i, el){
			var p = $(el).text();
			data += ',' + p;
		});
		data = data.replace(',','');
		$("#orderHidden").val(data);
		$("#orderControls").slideDown('fast');
	}
});

$("#orderSave").bind("click",function(){
	postRequest = $.post("../api/admin_designer_change_order.php", {
		order: $("#orderHidden").val()
	});
	postRequest.done(function(data){
		var obj = jQuery.parseJSON(data);
		if(obj.response.code != 200){
			alert(obj.response.message);
		} else {
			$("#orderControls").slideUp('fast');
		}
	});
});

<?php

if(count($designer['config']) != 0){
	foreach ($designer['config'] as $input_name => $input) { ?>
	<?php }
}

?>

function showPreview() {
	newwindow=window.open('designer_preview.php','Preview','height=600,width=800');
	if (window.focus) {newwindow.focus()}
	return false;
}
</script>