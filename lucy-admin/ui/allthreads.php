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
		$response = $cda->select(null,"threads",null);
	} catch (Exception $e) {
		die($e);
	}
	$threads = $response['data'];

	// Correcting issue if there is only one item in the database.
	if(isset($threads['id'])){
		$threads = array($threads);
	}

	getHeader("All threads");
	getNav(2); ?>
<h1>All threads</h1>
<table class="table table-hover">
	<thead>
		<tr>
			<th><strong>Owner</strong></th>
			<th><strong>Assigned To</strong></th>
			<th><strong>Subject</strong></th>
			<th><strong>Status</strong></th>
			<th><strong>Date</strong></th>
			<th><strong>Actions</strong></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($threads as $thread) {
	$data = json_decode($thread['data']);
	if(!empty($thread['assignedto'])){
		$rsp = $cda->select(array('name'),'userlist',array('id'=>$thread['assignedto']));
		$assignedto = $rsp['data']['name'];
	} else {
		$assignedto = "Nobody!";
		} ?>
	<tr>
		<td><?php echo($data->messages[0]->from->name); ?></td>
		<td><?php echo($assignedto); ?></td>
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
		<td><a href="view_thread.php?id=<?php echo($thread['id']); ?>">View</a><?php if($usr_Type == "Admin"){ ?> | <a href="del_thread.php?id=<?php echo($thread['id']); ?>">Delete</a><?php } ?></td>
	</tr>
<?php } ?>
</tbody>
</table>
	<?php getFooter(); ?>
</div>