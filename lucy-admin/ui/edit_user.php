<?php
	if($_GET['id'] == ""){
		die("No ID");
	}
	require("../session.php");
	require("../cda.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// User chose to save the settings.
	if(isset($_POST['submit'])){
		$user_name = addslashes($_POST['name']);
		$user_email = addslashes($_POST['email']);
		$user_type = addslashes($_POST['type']);
		try{
			$response = $cda->update("userlist", array("name"=>$user_name,"email"=>$user_email,"type"=>$user_type), array("id"=>$_GET['id']));
		} catch (Exception $e) {
			die($e);
		}
		$changes_Saved = True;
	}

	try {
		$response = $cda->select(array("name","email","type","id"),"userlist",array("id"=>$_GET['id']));
	} catch (Exception $e) {
		die($e);
	}
	$user = $response['data'];

	if($user['type'] == "Bot"){ die('Cannot modify settings for Bots.  <a href="users.php">Go Back</a>'); }

	getHeader("Edit User");
	getNav(4);
?>
<?php if($changes_Saved) { ?>
<div class="alert alert-success">
	<strong>Values Saved</strong>
</div>
<?php } ?>
<form class="form-horizontal" method="post">
	<h1>Edit User</h1>
	<div class="control-group">
		<label class="control-label">User Name:</label>
		<div class="controls">
			<input type="text" name="name" size="45" value="<?php echo($user['name']); ?>" title="The name of the user."/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">User Email:</label>
		<div class="controls">
			<input type="email" name="email" size="45" value="<?php echo($user['email']); ?>" title="The email of the user."/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">User Type:</label>
		<div class="controls">
			<select name="type">
				<option <?php if($user['type'] == "Admin") { echo('selected="selected"'); } ?>value="Admin">Admin</option>
				<option <?php if($user['type'] == "Agent") { echo('selected="selected"'); } ?>value="Agent">Agent</option>
				<option <?php if($user['type'] == "Client") { echo('selected="selected"'); } ?>value="Client">Client</option>
			</select>
		</div>
	</div>
	<input type="submit" name="submit" value="Save Changes" class="btn btn-primary"/>
</form>
<?php getFooter(); ?>