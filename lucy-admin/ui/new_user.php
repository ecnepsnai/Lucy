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
		$user_name = $_POST['name'];
		$user_email = $_POST['email'];
		$user_type = $_POST['type'];

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
		header("location: users.php?notice=create");
	}

writeDoc:
	getHeader("New User");
	getNav(3);
?>
<h1>Create New User</h1>
<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label class="col-sm-2 control-label">Name:</label>
		<div class="col-sm-3">
			<input type="text" class="form-control" name="name" required/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Email:</label>
		<div class="col-sm-3">
			<input type="email" class="form-control" name="email" required/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Password:</label>
		<div class="col-sm-3">
			<input type="password" class="form-control" name="password" required/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Type:</label>
		<div class="col-sm-3">
			<select name="type" class="form-control">
				<option value="Admin">Admin</option>
				<option value="Agent">Agent</option>
				<option value="Client">Client</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-3">
			<input type="submit" name="submit" value="Create User" class="btn btn-primary"/>
		</div>
	</div>
</form>
<?php getFooter(); ?>