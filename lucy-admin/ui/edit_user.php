<?php
	if(!isset($_GET['id'])){
		die("No ID");
	}
	require("../session.php");
	require("../sql.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	// User chose to save the settings.
	if(isset($_POST['submit'])){
		$user_name = addslashes($_POST['name']);
		$user_email = addslashes($_POST['email']);
		$user_type = addslashes($_POST['type']);
		$pwd_reset = addslashes($_POST['pwd_reset']);
		if($pwd_reset){
			$sql = "UPDATE userlist SET `name` = '" . $user_name . "', `email` = '" . $user_email . "', `type` = '" . $user_type . "', `pwd_reset` = '1' WHERE `id` = " . $_GET['id'] . ";";
		}
		$sql = "UPDATE userlist SET `name` = '" . $user_name . "', `email` = '" . $user_email . "', `type` = '" . $user_type . "' WHERE `id` = " . $_GET['id'] . ";";
		try{
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}
		$changes_Saved = True;
	}

	$sql = "SELECT name, email, type FROM userlist WHERE id = '" . addslashes($_GET['id']) . "'";
	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		die($e);
	}

	getHeader("Edit User");
	getNav(3);
?>
<?php if($changes_Saved) { ?>
<div id="notice">
	Values Saved
</div>
<?php } ?>
<form class="form-horizontal" method="post">
	<h2>Edit User</h2>
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
				<option <?php if($user['type'] == "Bot") { echo('selected="selected"'); } ?>value="Bot">Bot</option>
				<option <?php if($user['type'] == "Admin") { echo('selected="selected"'); } ?>value="Admin">Admin</option>
				<option <?php if($user['type'] == "Agent") { echo('selected="selected"'); } ?>value="Agent">Agent</option>
				<option <?php if($user['type'] == "Client") { echo('selected="selected"'); } ?>value="Client">Client</option>
			</select>
		</div>
	</div>
	<input type="submit" name="submit" value="Save Changes" class="btn btn-primary"/>
</form>
<?php getFooter(); ?>