<?php
	require("../session.php");
	require("default.php");
	include_once("../cda.php");
	
	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// Administrator or Agent access only.
	if($usr_Type != "Admin" && $usr_Type != "Agent"){
		lucy_die(0);
	}



	try {
		$response = $cda->select(null,"threads",array('assignedto'=>$usr_ID,'status'=>'Active'));
	} catch (Exception $e) {
		die($e);
	}
	$threads = $response['data'];

	// Correcting issue if there is only one item in the database.
	if(isset($threads['id'])){
		$threads = array($threads);
	}

	try {
		$response = $cda->select(array("status"),"threads",null);
	} catch (Exception $e) {
		die($e);
	}
	$counts = $response['data'];

	// Correcting issue if there is only one item in the database.
	if(isset($counts['status'])){
		$counts = array($counts);
	}
	$active = 0;
	$pending = 0;
	$closed = 0;
	foreach ($counts as $status) {
		switch($status['status']){
			case 'Active':
				$active ++;
			break;
			case 'Pending':
				$pending ++;
			break;
			case 'Closed':
				$closed ++;
			break;
		}
	}

	getHeader("Dashboard");
	getNav(0);
?>
<div class="row">
	<div class="col-md-6">
		<h2>Your Active Threads</h2>
		<table class="table table-hover">
			<thead>
				<tr>
					<th><strong>Owner</strong></th>
					<th><strong>Subject</strong></th>
					<th><strong>Date</strong></th>
					<th><strong>Actions</strong></th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($threads as $thread) {
			$data = json_decode($thread['data']); ?>
			<tr>
				<td><?php echo($data->messages[0]->from->name); ?></td>
				<td><?php echo(substr($thread['subject'], 0, 25)); ?>...</td>
				<td><?php echo(date_format(date_create($thread['date']), 'd/m/Y')); ?></td>
				<td><a href="view_thread.php?id=<?php echo($thread['id']); ?>">View</a><?php if($usr_Type == "Admin"){ ?> | <a href="del_thread.php?id=<?php echo($thread['id']); ?>">Delete</a><?php } ?></td>
			</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h2>All Threads</h2>
		<div id="chart_threads"></div>
	</div>
</div>
<?php getFooter(); ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
	var data = google.visualization.arrayToDataTable([
		['Status', 'Count'],
		['Active', <?php echo($active); ?>],
		['Pending', <?php echo($pending); ?>],
		['Closed', <?php echo($closed); ?>]
	]);

	var chart = new google.visualization.PieChart(document.getElementById('chart_threads'));
	chart.draw(data);
}
</script>