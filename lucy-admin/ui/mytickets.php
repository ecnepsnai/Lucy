<?php
	require("../session.php");
	require("../cda.php");
	require("default.php");

	// Administrator or Agent access only.
	if($usr_Type != "Admin" && $usr_Type != "Agent"){
		lucy_die(0);
	}

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	try {
		$response = $cda->select(null,"ticketlist",array("assignedto"=>$GLOBALS['usr_ID']));
	} catch (Exception $e) {
		die($e);
	}
	$tickets = $response['data'];

	// Correcting issue if there is only one item in the database.
	if(isset($tickets['id'])){
		$tickets = array($tickets);
	}

	getHeader("My Tickets");
	getNav(1);
?>
<h1>My Tickets</h1>
<table class="table table-hover">
	<thead>
		<tr>
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
		<td><a href="view_ticket.php?id=<?php echo($ticket['id']); ?>">View</a><?php if($usr_Type == "Admin"){ ?> | <a href="del_ticket.php?id=<?php echo($ticket['id']); ?>">Delete</a><?php } ?></td>
	</tr>
<?php } ?>
</tbody>
</table>
	<?php getFooter(); ?>
</div>