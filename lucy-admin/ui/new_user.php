<?php
	require("../session.php");
	require("../cda.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	// User chose to save the settings.
	if(isset($_POST['submit'])){
		// Creating the CDA class.
		$cda = new cda;
		// Initializing the CDA class.
		$cda->init($GLOBALS['config']['Database']['Type']);
		$user_name = addslashes($_POST['name']);
		$user_email = addslashes($_POST['email']);
		$user_type = addslashes($_POST['type']);

		// Generating a random salt used for encryption.
		$salt = mt_rand(10, 99);

		// Encrypting the password.
		$hashed_password = md5($salt . md5($_POST['password']));

		// Creating the SQL statment.
		try{
			$response = $cda->insert("userlist",array("type","name","email","password","date_registered","salt"),array($user_type,$user_name,$user_email,$hashed_password,date("Y-m-d"),$salt));
		} catch (Exception $e){
			$signup_error = $e;
			goto writeDoc;
		}
		$changes_Saved = True;
	}
	getHeader("New User");
	getNav(3);
?>
<?php if($changes_Saved) { ?>
<div id="notice">
	Values Saved
</div>
<?php } ?>
<form class="form-horizontal" method="post">
	<h2>Create a New User</h2>
	<div class="control-group">
		<label class="control-label">User Name:</label>
		<div class="controls">
			<input type="text" name="name" size="45" title="The name of the user."/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">User Email:</label>
		<div class="controls">
			<input type="email" name="email" size="45" title="The email of the user."/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">User Password:</label>
		<div class="controls">
			<input type="password" name="password" size="45" title="The password of the user."/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">User Type:</label>
		<div class="controls">
			<select name="type">
				<option value="Admin">Admin</option>
				<option value="Agent">Agent</option>
				<option value="Client">Client</option>
			</select>
		</div>
	</div>
	<input type="submit" name="submit" class="btn btn-primary" value="Create User" />
</form>
<?php getFooter(); ?>