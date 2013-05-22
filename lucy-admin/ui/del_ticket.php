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

	// User chose to delete the ticket.
	if(isset($_POST['submit'])){
		$sql = "DELETE FROM ticketlist WHERE id = '" . $_GET['id'] . "';";
		echo($sql);
		try{
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}
		$sql = "DROP TABLE " . $_GET['id'] . ";";
		echo($sql);
		try{
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}
		// Dies when complete.
		header("Location: alltickets.php");
	}

	// User chose not to delete the ticket
	if(isset($_POST['reset'])){
		header("Location: alltickets.php");
	}
	getHeader("Delete Ticket");
	getNav(2);
?>
<form name="usrsetngs" method="post">
	<div class="alert alert-block alert-error fade in">
		<h4 class="alert-heading">Are you sure you want to delete this ticket?</h4>
		<p>Deleting this ticket will remove it completely from the database.  Are you sure you don't want to just <a href="view_ticket.php?id=<?php echo($_GET['id']); ?>">close</a> it instead?</p>
		<p>
			<input type="submit" name="submit" value="Delete Forever" class="btn btn-danger"/> <input type="submit" name="reset" value="Maybe Later" class="btn"/>
		</p>
	</div>
</form>
<?php getFooter(); ?>