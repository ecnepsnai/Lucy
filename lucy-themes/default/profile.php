<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Profile'); ?>
<h1>My Profile</h1>
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
<form method="post" name="edit_user" onsubmit="return validateForm()" >
	<table>
		<tr>
			<td>
				Current Password: (Only needed if you're changing your password)<br/>
				<input type="password" name="cur_password" size="30"/><br/>
				New Password:<br/>
				<input type="password" name="new_password" size="30"/><br/>
				New Password (Again):<br/>
				<input type="password" name="new_password_rep" size="30"/>
			</td>
		</tr>
		<tr>
			<td>
				User Name:<br/>
				<input type="text" name="name" size="30" value="<?php echo($user['name']); ?>" title="The name of the user."/>
			</td>
		</tr>
		<tr>
			<td>
				User Email:<br/>
				<input type="email" name="email" size="30" value="<?php echo($user['email']); ?>" title="The email of the user."/>
			</td>
		</tr>
	</table>
	<div id="buttons">
		<input type="submit" name="submit" value="Save Changes"/> <input type="reset" name="reset" value="Delete User"/>
	</div>
</form>
<?php getFooter(); ?>
</div>