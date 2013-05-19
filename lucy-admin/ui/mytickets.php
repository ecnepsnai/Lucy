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
	getNav(1);
?><h1>My Tickets</h1>
<table class="table table-hover">
	<thead>
		<tr>
			<th><strong>Name</strong></th>
			<th><strong>Application</strong></th>
			<th><strong>Status</strong></th>
			<th><strong>Date</strong></th>
			<th><strong>Subject</strong></th>
			<th><strong>Actions</strong></th>
		</tr>
	</thead>
	<tbody>
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
		<td><?php echo($user['subject']); ?></td>
		<td><a href="view_ticket.php?id=<?php echo($ticket['id']); ?>">View</a> | <a href="">Delete</a></td>
	</tr>
<?php } ?>
</tbody>
</table>
	<?php getFooter(); ?>
</div>