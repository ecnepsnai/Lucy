<?php
	if(!isset($_GET['id'])){
		die("No ID");
	}
	require("../session.php");
	require("../cda.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	// User chose to delete the thread.
	if(isset($_POST['submit'])){
		// Creating the CDA class.
		$cda = new cda;
		// Initializing the CDA class.
		$cda->init($GLOBALS['config']['Database']['Type']);
		try{
			$cda->delete("threads",array("id"=>$_GET['id']));
		} catch (Exception $e) {
			die($e);
		}
		
		// Dies when complete.
		header("Location: allthreads.php?notice=del");
	}

	// User chose not to delete the thread
	if(isset($_POST['reset'])){
		header("Location: allthreads.php");
	}
	getHeader("Delete thread");
	getNav(2);
?>
<form name="usrsetngs" method="post">
	<h4 class="alert-heading">Are you sure you want to delete this thread?</h4>
	<p>Deleting this thread will remove it completely from the database.  Are you sure you don't want to just <a href="view_thread.php?id=<?php echo($_GET['id']); ?>">close</a> it instead?</p>
	<p>
		<input type="submit" name="submit" value="Delete Forever" class="btn btn-danger"/> <input type="submit" name="reset" value="Maybe Later" class="btn"/>
	</p>
</form>
<?php getFooter(); ?>