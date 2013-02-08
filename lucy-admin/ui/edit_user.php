<?php
	if(!isset($_GET['id'])){
		die("No ID");
	}
	require("../session.php");
	require("../sql.php");
	require("default.php");

	// Administrator access only.
	if(!$usr_Type == "Admin"){
		die("Forbidden.");
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

	// User chose to delete the user.
	if(isset($_POST['reset'])){

	}

	$sql = "SELECT name, email, type FROM userlist WHERE id = '" . addslashes($_GET['id']) . "'";
	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		die($e);
	}

	getHeader("Edit User");
	getSidebar(2);
?>
		<div id="content">
			<?php if($changes_Saved) { ?>
			<div id="notice">
				Values Saved
			</div>
			<?php } ?>
			<form name="usrsetngs" method="post">
				<div id="tabs">
					<h2>Edit User</h2>
					<div>
						<table>
							<tr>
								<td>
									User Name:<br/>
									<input type="text" name="name" size="45" value="<?php echo($user['name']); ?>" title="The name of the user."/>
								</td>
							</tr>
							<tr>
								<td>
									User Email:<br/>
									<input type="email" name="email" size="45" value="<?php echo($user['email']); ?>" title="The email of the user."/>
								</td>
							</tr>
							<tr>
								<td>
									User Type:<br/>
									<input type="radio" name="type" value="Admin"<?php if($user['type'] == 'Admin'){echo('checked="checked"');} ?> title="Administrator"/>Administrator<br/>
									<input type="radio" name="type" value="Client"<?php if($user['type'] == 'Client'){echo('checked="checked"');} ?> title="Client"/>Client
								</td>
							</tr>
							<tr>
								<td>
									<input type="checkbox" name="pwd_reset" /> User Must Change Password
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="buttons">
					<input type="submit" name="submit" value="Save Changes"/> <input type="reset" name="reset" value="Delete User"/>
				</div>
			</form>
		</div>
	</div>
	<?php getFooter(); ?>
</div>