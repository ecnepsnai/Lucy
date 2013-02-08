<?php
	require("../session.php");
	require("../sql.php");
	require("default.php");

	// Administrator access only.
	if(!$usr_Type == "Admin"){
		die("Forbidden.");
	}

	// Get the total count of tickets.
	$sql = "SELECT id FROM ticketlist";
	try {
		$tickets = sqlQuery($sql, False);
		if(!$tickets){
			$ticketcount = 0;
		} else {
			$ticketcount = count($tickets);
		}
	} catch (Exception $e) {
		$ticketcount = 0;
	}

	// Get the total count of tickets.
	$sql = "SELECT id FROM ticketlist WHERE lastreply = 'Client'";
	try {
		$replys = sqlQuery($sql, False);
		if(!$replys){
			$replycount = 0;
		} else {
			$replycount = count($replys);
		}
	} catch (Exception $e) {
		$replycount = 0;
	}

	// Get the total count of users.
	$sql = "SELECT id FROM userlist";
	try {
		$users = sqlQuery($sql, False);
		if(!$users){
			$users = 0;
		} else {
			$usercount = count($users);
		}
	} catch (Exception $e) {
		$usercount = 0;
	}
	getHeader("Dashboard");
	getSidebar(0);
?>
		<div id="content">
			<h2>Welcome back, <?php echo($usr_Name); ?>.  Here's what's happening right now.</h2>
			<ul>
				<li><strong><?php echo($ticketcount); ?></strong> Tickets.</li>
				<li><strong><?php echo($replycount); ?></strong> Tickets awaiting response.</li>
				<li><strong><?php echo($usercount); ?></strong> Users.</li>
			</ul>
		</div>
	</div>
	<?php getFooter(); ?>
</div>