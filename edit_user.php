<?php
	require("assets/lib/session.php");

	// This page requires a user to be signed in.
	if($usr_IsSignedIn != True) {
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?notice=login\">Redirecting...");
	}

	// Getting the ID.
	if($usr_Type == "Admin"){
		if(isset($_GET['id'])){
			$id = $_GET['id'];
		} else {
			$id = $usr_ID;
		}
	} else {
		$id = $usr_ID;
	}
	require("assets/lib/sql.php");
	$id = addslashes($id);

	// User chose to edit the user.
	if(isset($_POST['submit'])){
		$name = addslashes(trim($_POST['name']));
		$email = addslashes(trim($_POST['email']));
		$specs = addslashes(trim($_POST['specs']));
		$sql = "UPDATE userlist SET name = '" . $name . "', email = '" . $email . "', rig_specs = '" . $specs . "' WHERE id = '" . $id . "';";
		try{
			sqlQuery($sql, False);
		} catch (Exception $e){
			require("error_db.php");
		}
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "profile.php?id=" . $id . "&change=save\">Redirecting...");
	}
	$sql = "SELECT * FROM userlist WHERE id ='" . $id . "';";
	try{
		$user = sqlQuery($sql, True);
	} catch (Exception $e){
		require("error-db.php");
	}
documentCreate("Edit User", False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
<h1>Edit User: <?php echo($user['name']); ?></h1>
<script type="text/javascript">
	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function validateForm() {
		var x_name=document.forms["fm_edituser"]["name"].value;
		var x_email=document.forms["fm_edituser"]["email"].value;
		var x_password=document.forms["fm_edituser"]["pwd"].value;

		if(x_name = "" || x_email == "" || x_password == "") {
			alert("A required field is blank.");
			return false;
		}
		if(!validateEmail(x_email)) {
			alert("The email address you provided was not valid.");
			return false;
		}
		return true;
	}
</script>
<form method="POST" name="fm_edituser" onSubmit="return validateForm()">
	<p>Name:<br/><input type="text" name="name" class="txtglow" size="45" value="<?php echo($user['name']); ?>"/></p>
	<p>Email Address:<br/><input type="email" name="email" class="txtglow" size="45" value="<?php echo($user['email']); ?>"/></p>
	<p>Your computers specifications: <a href="help_specs.php">Help</a><br/><textarea name="specs" class="txtglow" rows="10" cols="75" placeholder="Example: Windows 8 64bit.  AMD-FX8150, 16GB of Memory, 2TB of Hard-Drive space."><?php echo($user['rig_specs']); ?></textarea>
	<p><input type="submit" name="submit" value="Save Changes" class="btn" id="blue"/></p>
</form>
</div>
<?php writeFooter(); ?>
</div>