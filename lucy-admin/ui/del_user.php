<?php
	if(!isset($_GET['id'])){
		die("No ID");
	}
	if($_GET['id'] == 1){
		die('Cannot delete the root user. <a href="index.php">Back</a>');
	}
	require("../session.php");
	require("../cda.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	// User chose to delete the user.
	if(isset($_POST['submit'])){
		// Creating the CDA class.
		$cda = new cda;
		// Initializing the CDA class.
		$cda->init($GLOBALS['config']['Database']['Type']);
		try{
			$cda->delete("userlist",array("id"=>$_GET['id']));
		} catch (Exception $e) {
			die($e);
		}
		// Dies when complete.
		header("Location: users.php");
	}

	// User chose not to delete the user
	if(isset($_POST['reset'])){
		header("Location: users.php");
	}
	getHeader("Delete User");
	getNav(4);
?>
<form name="usrsetngs" method="post">
	<div class="alert alert-block alert-error fade in">
		<h4 class="alert-heading">Are you sure you want to delete this user?</h4>
		<p>Deleting this user will not delete any threads they created, they will not be able to login anymore.</p>
		<p>
			<input type="submit" name="submit" value="Delete Forever" class="btn btn-danger"/> <input type="submit" name="reset" value="Maybe Later" class="btn"/>
		</p>
	</div>
</form>
<?php getFooter(); ?>