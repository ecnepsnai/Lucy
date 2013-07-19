<?php

	function getHeader($title) { ?>
<!DOCTYPE html>
<meta charset="utf-8">
<title><?php echo($title); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="assets/css/bootstrap.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<style type="text/css">
body {
	padding-top: 60px;
	padding-bottom: 40px;
}
</style>
<link rel="shortcut icon" href="assets/img/favicon.png">
	<?php } 

	function getNav($pageIndex) { ?>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="index.php">Lucy Administration</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li <?php if($pageIndex == 0){ echo('class="active"'); } ?>><a href="index.php">Dashboard</a></li>
						<li <?php if($pageIndex == 1){ echo('class="active"'); } ?>><a href="mytickets.php">My Tickets</a></li>
						<li <?php if($pageIndex == 2){ echo('class="active"'); } ?>><a href="alltickets.php">All Tickets</a></li>
						<?php if($GLOBALS['usr_Type'] == "Admin") { echo('<li '); if($pageIndex == 4){ echo('class="active"'); } echo('><a href="users.php">Users</a></li>'); } ?>
						<?php if($GLOBALS['usr_Type'] == "Admin") { echo('<li '); if($pageIndex == 5){ echo('class="active"'); } echo('><a href="settings.php">Settings</a></li>'); } ?>
					</ul>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hey, <?php echo($GLOBALS['usr_Name']); ?> <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="profile.php">My Profile</a></li>
								<li><a href="auth.php">Two-Factor Config</a></li>
								<li class="divider"></li>
								<li><a href="../../logout.php">Log out</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
	<?php }

	function getFooter() {?>
<footer style="text-align:center">
	<hr/>
	<p>Lucy was created by Ian Spence.  Version: <?php echo($GLOBALS['readonly']['version']); ?></p>
	<img src="assets/img/logo.png" alt="Lucy"/>
</footer>

</div>
	<?php }

	function lucy_die($reason) {
		getHeader("Error");
		if($GLOBALS['usr_Type'] == "Agent" &&	 $GLOBALS['usr_Type'] == "Admin"){
			getNav(999);
		} else {
			echo('<div class="container">');
		}

		switch ($reason) {
			case 0: ?>
<div class="alert alert-block alert-error fade in">
	<h4 class="alert-heading">Access Denied</h4>
	<p>Only approved administrators may access this page.</p>
</div>
			<?php
			break;
		}
		getfooter();
		die();
	}