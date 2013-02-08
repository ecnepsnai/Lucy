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

	// User chose to delete the user.
	if(isset($_POST['submit'])){
		$sql = "DELETE FROM userlist WHERE `id` = " . $_GET['id'] . ";";
		try{
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}
		// Dies when complete.
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=users.php\">Redirecting...");
	}

	// User chose not to delete the user
	if(isset($_POST['reset'])){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=users.php\">Redirecting...");
	}
	getHeader("Delete User");
	getSidebar(2);
?>
	<div id="content">
		<form name="usrsetngs" method="post">
			<h2>Are you sure you want to delete this user?</h2>
			<div id="buttons">
				<input type="submit" name="submit" value="Delete Forever"/><input type="submit" name="reset" value="Maybe Later"/>
			</div>
		</form>
	</div>
</div>
<?php getFooter(); ?>
</div>