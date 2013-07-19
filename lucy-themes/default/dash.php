<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Dash'); getNav(0); ?>
<h1>Your tickets</h1>
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
				echo('<span class="label">Pending</span>');
			} elseif ($ticket['status'] == "Active"){
				echo('<span class="label label-success">Active</span>');
			} else {
				echo('<span class="label label-inverse">Closed</span>');
			} ?></td>
		<td><?php echo(date_format(date_create($ticket['date']), 'd/m/Y')); ?></td>
		<td><?php echo($ticket['subject']); ?>...</td>
		<td><a href="ticket.php?id=<?php echo($ticket['id']); ?>">View</a></td>
	</tr>
<?php } ?>
</tbody>
</table>
<?php getFooter(); ?>