<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Profile'); getNav(2); ?>
<h1><?php echo($usr_Name); ?></h1>
<hr/>
<script type="text/javascript">
	function validateForm(){
		if(document.forms["edit_user"]["new_password"].value != document.forms["edit_user"]["new_password_rep"].value) {
			alert("New passwords do not match.");
			return false;
		}
		if(document.forms["edit_user"]["name"].value == null || document.forms["edit_user"]["name"].value == "") {
			alert("Name field cannot be empty.");
			return false;
		}
		if(document.forms["edit_user"]["email"].value == null || document.forms["edit_user"]["email"].value == "") {
			alert("Email field cannot be empty.");
			return false;
		}
	}
</script>
<form method="post" name="edit_user" onsubmit="return validateForm()" class="form-horizontal">
	<h4>Change your Password:</h4>
	<div class="control-group">
		<label class="control-label">Current Password:</label>
		<div class="controls">
			<input type="password" name="cur_password" size="30"/><br/>
		</div>
	</div>
	<div class="control-group">	
		<label class="control-label">New Password:</label>
		<div class="controls">
			<input type="password" name="new_password" size="30"/>
		</div>
	</div>
	<div class="control-group">	
		<label class="control-label">New Password (Again):</label>
		<div class="controls">
			<input type="password" name="new_password_rep" size="30"/>
		</div>
	</div>
	<hr/>
	<div class="control-group">
		<label class="control-label">User Name:</label>
		<div class="controls">
			<input type="text" name="name" size="30" value="<?php echo($user['name']); ?>" title="The name of the user."/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">User Email:</label>
		<div class="controls">
			<input type="email" name="email" size="30" value="<?php echo($user['email']); ?>" title="The email of the user."/>
		</div>
	</div>
	<input type="submit" name="submit" value="Save Changes" class="btn btn-primary"/> <input type="reset" name="reset" value="Delete Account" class="btn"/>
</form>
<?php getFooter(); ?>
</div>