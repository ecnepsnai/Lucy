<?php
	require("../session.php");
	require("../sql.php");
	require("default.php");

	// Administrator access only.
	if(!$usr_Type == "Admin"){
		die("Forbidden.");
	}

	$sql = "SELECT * FROM ticketlist";
	try {
		$tickets = sqlQuery($sql, False);
	} catch (Exception $e) {
		die($e);
	}

	getHeader("All Tickets");
	getSidebar(2);
?>
		<div id="content">
			<h2>All Tickets</h2>
			<table>
				<tr>
					<td><strong>Name</strong></td>
					<td><strong>Application</strong></td>
					<td><strong>Status</strong></td>
					<td><strong>Date</strong></td>
					<td><strong>Subject</strong></td>
					<td><strong>Assigned To</strong></td>
					<td><strong>Actions</strong></td>
				</tr>
			<?php foreach ($tickets as $ticket) { ?>
				<tr>
					<td><?php echo($ticket['name']); ?></td>
					<td><?php echo($ticket['application']); ?></td>
					<td><?php
						if($ticket['status'] == "Pending"){
							echo('<span class="status_pending">Pending</span>');
						} elseif ($ticket['status'] == "Active"){
							echo('<span class="status_active">Active</span>');
						} else {
							echo('<span class="status_closed">Closed</span>');
						} ?></td>
					<td><?php echo(date_format(date_create($ticket['date']), 'd/m/Y')); ?></td>
					<td><?php echo($ticket['subject']); ?>...</td>
					<td><?php
					if($ticket['assignedto'] == 0) {
						echo("<em>Nobody!</em>");
					} else {
						$sql = "SELECT name FROM userlist WHERE id = '" . $ticket['assignedto'] . "'";
						try {
							$user = sqlQuery($sql, True);
						} catch (Exception $e) {
							die($e);
						}
					echo($user['name']);
					} ?></td>
					<td><a href="view_ticket.php?id=<?php echo($ticket['id']); ?>">View</a> | <a href="">Delete</a></td>
				</tr>
			<?php } ?>
			</table>
		</div>
	</div>
	<?php getFooter(); ?>
</div>