<?php
	require("../session.php");
	writeRedirect(False, False, True);

	// Get the search filters.
	$application = $_GET['a'];
	$version = $_GET['v'];
	$os = $_GET['o'];
	$status = $_GET['s'];

	require("../db_connect.php");

	$hasFilters = False;

	// Check for filter, if none: use normal SQL.
	if(empty($application) && empty($version) && empty($os) && empty($status)){
		$sql = "SELECT * FROM ticketlist";
	} else {
		$hasFilters = True;
		// We need an initial value that will always return true so that we can just concatenate "AND ..." to the query.
		$sql = "SELECT * FROM ticketlist WHERE 1=1";
		if(isset($application)){
			$sql.= " AND application = '" . mysql_real_escape_string($application) . "'";
		}
		if(isset($version)){
			$sql.= " AND version = '" . mysql_real_escape_string($version) . "'";
		}
		if(isset($os)){
			$sql.= " AND os = '" . mysql_real_escape_string($os) . "'";
		}
		if(isset($status)){
			$sql.= " AND status = '" . mysql_real_escape_string($status) . "'";
		}
	}
	echo($sql);
	$ticket_request = mysql_query($sql);
	if(!$ticket_request){
		require("../error_db.php");
	}
	$sql = "SELECT name FROM applist";
	$app_request = mysql_query($sql);
	if(!$app_request){
		require("../error_db.php");
	}
?>
<?php documentCreate(TITLE_DASH, True, False, null, null); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<?php if($hasFilters == False){?><a href="javascript:showFilters()" id="shwfilt">Show Filters</a><?php } ?>
		<script>
			function showFilters() {
				$('#shwfilt').hide();
				$('#filter').show();
			}
		</script>
		<div id="filter" <?php if($hasFilters == False){echo("style=\"display:none;\"");} ?>>
			<form method="GET">
				<p>Application:<br/>
					<select name="a">
						<option value="">Any</option>
						<?php while($app = mysql_fetch_array($app_request)) {
							echo('<option value="' . $app['name'] . '">' . $app['name'] . '</option>');
						}
						?>
					</select>
				</p>
					<p>Version:<br/>
					<input type="text" name="v" maxlength="20" />
				</p>
				<p>OS:<br/>
					<select name="o">
						<option value="">Any</option> <option disabled="disabled">Microsoft Windows</option> <option value="WinXP">Windows XP</option> <option value="WinVx32">Windows Vista (32bit)</option>
						<option value="WinVx64">Windows Vista (64bit)</option> <option value="Win7x32">Windows 7 (32bit)</option> <option value="Win7x64">Windows 7 (64bit)</option> <option value="Win8x32">Windows 8 (RT)</option>
						<option value="Win8x64">Windows 8 (64bit / Pro)</option> <option disabled="disabled">Mac OS X</option> <option value="OSX106">Mac OS X Leopard</option> <option value="OSX107">Mac OS X Snow Leopard</option>
						<option value="OSX108">Mac OS X Lion</option> <option value="OSX109">Mac OS X Mountain Lion</option> <option disabled="disabled">Ubuntu</option> <option value="UBU10.10">Ubuntu: 10.10 - Maverick Meerkat</option>
						<option value="UBU11.04">Ubuntu: 11.04 - Natty Narwhal</option> <option value="UBU11.10">Ubuntu: 11.10 - Oneiric Ocelot</option> <option value="UBU12.04">Ubuntu: 12.04LTS - Precise Pangolin</option>
						<option value="UBU12.10">Ubuntu: 12.10 - Quantal Quetzal</option> <option disabled="disabled">Apple iOS</option> <option value="iOS4">iOS 4</option> <option value="iOS5">iOS 5</option> <option value="iOS6">iOS 6</option>
					</select>
				</p>
				<p>Status:<br/>
					<select name="s">
						<option value="">Any</option>
						<option value="open">Open</option>
						<option value="closed">Closed</option>
					</select>
				</p>
				<p>
					<input type="submit" class="btn" id="blue" value="Refine Search"/><br/>
				</p>
			</form>
		</div>
	<?php 
		if(mysql_num_rows($ticket_request) == 0){
			echo('<h2>No tickets found.</h2>');
		} else {
			echo('<h2>Current Tickets</h2>');
			while($ticket_info = mysql_fetch_array($ticket_request)) { ?>
				<div class="ticket_info" onClick="parent.location='<?php echo(SERVER_DOMAIN . "ticket.php?id=" . $ticket_info['id']); ?>'" style="cursor:pointer">
				<strong>Ticket ID:</strong> <?php echo($ticket_info['id']); ?><br/>
				<strong>Application:</strong> <?php echo($ticket_info['application']); ?><br/>
				<strong>Version:</strong> <?php echo($ticket_info['version']); ?><br/>
				<strong>Operating System:</strong> <?php echo($ticket_info['os']); ?><br/>
				<strong>Status:</strong> <?php echo($ticket_info['status']); ?><hr/>
				<?php echo($ticket_info['subject'] . '...'); ?>
				</div>
	<?php } } ?>
</div>
<?php writeFooter(); ?>
</div>