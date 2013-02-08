<?php
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

		// Generating a random salt used for encryption.
		$salt = mt_rand(10, 99);

		// Encrypting the password.
		$hashed_password = md5($salt . md5($_POST['password']));

		// Creating the SQL statment.
		$sql = "INSERT INTO  userlist (type, name, email, password, date_registered, salt) VALUES ('" . $user_type . "',  '" . $user_name . "',  '" . $user_email . "',  '" . $hashed_password . "',  '" . date("Y-m-d") . "', '". $salt ."');";
		try{
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}
		$changes_Saved = True;
	}
	getHeader("New User");
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
					<h2>New User</h2>
					<div>
						<table>
							<tr>
								<td>
									User Name:<br/>
									<input type="text" name="name" size="45" title="The name of the user."/>
								</td>
							</tr>
							<tr>
								<td>
									User Email:<br/>
									<input type="email" name="email" size="45" title="The email of the user."/>
								</td>
							</tr>
							<tr>
								<td>
									User Password:<br/>
									<input type="password" name="password" size="45" title="The password of the user."/>
								</td>
							</tr>
							<tr>
								<td>
									User Type:<br/>
									<input type="radio" name="type" value="Admin" title="Administrator"/>Administrator<br/>
									<input type="radio" name="type" value="Client" title="Client"/>Client
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="buttons">
					<input type="submit" name="submit" value="Save Changes"/>
				</div>
			</form>
		</div>
	</div>
	<?php getFooter(); ?>
</div>