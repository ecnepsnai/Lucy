<?php
	require("../session.php");
	require("../sql.php");
	require("default.php");

	// Administrator access only.
	if(!$usr_Type == "Admin"){
		die("Forbidden.");
	}

	$sql = "SELECT * FROM ticketlist WHERE assignedto = '" . $GLOBALS['usr_ID'] . "'";
	try {
		$tickets = sqlQuery($sql, False);
	} catch (Exception $e) {
		die($e);
	}

	getHeader("My Tickets");
	getSidebar(1);
?>
		<div id="content">
			<h2>My Tickets</h2>
			<table>
				<tr>
					<td><strong>Name</strong></td>
					<td><strong>Application</strong></td>
					<td><strong>Status</strong></td>
					<td><strong>Date</strong></td>
					<td><strong>Subject</strong></td>
					<td><strong>Actions</strong></td>
				</tr>
			<?php foreach ($tickets as $ticket) { ?>
				<tr>
					<td><?php echo($ticket['name']); ?></td>
					<td><?php echo($ticket['application']); ?></td>
					<td><?php echo($ticket['status']); ?></td>
					<td><?php echo(date_format(date_create($ticket['date']), 'd/m/Y')); ?></td>
					<td><?php echo($ticket['subject']); ?>...</td>
					<td><a href="view_ticket.php?id=<?php echo($ticket['id']); ?>">View</a> | <a href="">Delete</a></td>
				</tr>
			<?php } ?>
			</table>
		</div>
	</div>
	<?php getFooter(); ?>
</div>