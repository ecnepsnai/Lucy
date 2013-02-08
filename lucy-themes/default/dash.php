<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Dash'); ?>
<h1>Your tickets</h1>
<table>
	<tr>
		<td><strong>Application</strong></td>
		<td><strong>Status</strong></td>
		<td><strong>Date</strong></td>
		<td><strong>Subject</strong></td>
		<td><strong>Actions</strong></td>
	</tr>
<?php foreach ($tickets as $ticket) { ?>
	<tr>
		<td><?php echo($ticket['application']); ?></td>
		<td><?php echo($ticket['status']); ?></td>
		<td><?php echo(date_format(date_create($ticket['date']), 'd/m/Y')); ?></td>
		<td><?php echo($ticket['subject']); ?>...</td>
		<td><a href="ticket.php?id=<?php echo($ticket['id']); ?>">View</a></td>
	</tr>
<?php } ?>
</table>
<?php getFooter(); ?>