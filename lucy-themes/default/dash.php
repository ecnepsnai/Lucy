<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Dash'); getNav(0); 

if($_GET['verify'] == 'sent'){ ?>
<div class="alert alert-success">
	<strong>Verification Email Sent</strong> Check your email and click the link to verify your email address.
</div>
<?php } elseif($_GET['verify'] == 'confirmed'){ ?>
<div class="alert alert-success">
	<strong>Email Verified</strong> Your email address had been verified.  Thank you.
</div>
<?php }
if($_GET['notice'] == 'nothread'){ ?>
<div class="alert alert-error">
	<strong>Thread Not Found</strong> That thread does not exist.
</div>
<?php } elseif($_GET['notice'] == 'denied'){ ?>
<div class="alert alert-error">
	<strong>Whoops</strong> You're not allowed to see that.
</div>
<?php } else if($_GET['notice'] == 'readonly'){ ?>
<div class="alert alert-danger">
	<strong>Unable to Create Thread - </strong> Lucy is in Read-Only Mode, only approved administrators may log in.
</div>
<?php } ?>
<h1>Your threads</h1>
<table class="table table-hover">
	<thead>
		<tr>
			<th><strong>Subject</strong></th>
			<th><strong>Status</strong></th>
			<th><strong>Date</strong></th>
			<th><strong>Actions</strong></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($threads as $thread) { ?>
	<tr>
		<td><?php echo($thread['subject']); ?>...</td>
		<td><?php
			if($thread['status'] == "Pending"){
				echo('<span class="label label-info">Pending</span>');
			} elseif ($thread['status'] == "Active"){
				echo('<span class="label label-success">Active</span>');
			} else {
				echo('<span class="label label-default">Closed</span>');
			} ?></td>
		<td><?php echo(date_format(date_create($thread['date']), 'd/m/Y')); ?></td>
		<td><a href="thread.php?id=<?php echo($thread['id']); ?>">View</a></td>
	</tr>
<?php } ?>
</tbody>
</table>
<?php getFooter(); ?>