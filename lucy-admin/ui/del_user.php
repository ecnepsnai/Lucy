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
	getNav(3);
?>
<form name="usrsetngs" method="post">
	<div class="alert alert-block alert-error fade in">
		<h4 class="alert-heading">Are you sure you want to delete this user?</h4>
		<p>Deleting this user will not delete any tickets they created, they will not be able to login anymore.</p>
		<p>
			<input type="submit" name="submit" value="Delete Forever" class="btn btn-danger"/> <input type="submit" name="reset" value="Maybe Later" class="btn"/>
		</p>
	</div>
</form>
<?php getFooter(); ?>