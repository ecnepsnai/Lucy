<?php

	function getHeader($title) { ?>
<!DOCTYPE html>
<meta charset="utf-8">
<title><?php echo($title); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<style type="text/css">body {padding-top: 1em;}</style>
<link rel="shortcut icon" href="assets/img/favicon.png">
	<?php } 

	function getNav($pageIndex) { ?>
	<div class="container">
		<?php if($GLOBALS['config']['ReadOnly'] == true){ ?><div class="alert alert-warning"><strong>Lucy is in Read-Only Mode;</strong> only administrators can log in and make changes.<br><a href="readonly.php" class="btn btn-default">Make Public</a></div><?php } ?>
		<nav class="navbar navbar-inverse" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#lucy-admin-navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php">Lucy Administration</a>
				</div>
				<div class="collapse navbar-collapse" id="lucy-admin-navbar">
					<ul class="nav navbar-nav">
						<li <?php if($pageIndex == 0){ echo('class="active"'); } ?>><a href="index.php">Dashboard</a></li>
						<li <?php if($pageIndex == 1){ echo('class="active"'); } ?>><a href="threads.php">Threads</a></li>
						<?php if($GLOBALS['usr_Type'] == "Admin") { echo('<li '); if($pageIndex == 4){ echo('class="active"'); } echo('><a href="users.php">Users</a></li>'); } ?>
						<?php if($GLOBALS['usr_Type'] == "Admin") { echo('<li '); if($pageIndex == 5){ echo('class="active"'); } echo('><a href="designer.php">Designer</a></li>'); } ?>
						<?php if($GLOBALS['usr_Type'] == "Admin") { echo('<li '); if($pageIndex == 6){ echo('class="active"'); } echo('><a href="settings.php">Settings</a></li>'); } ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img style="display:inline;border-radius:2px;" src="http://www.gravatar.com/avatar/<?php echo(md5($GLOBALS['usr_Email'])); ?>?s=18&d=mm"> Hey, <?php echo($GLOBALS['usr_Name']); ?> <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="../../profile.php">My Profile</a></li>
								<li class="divider"></li>
								<li><a href="../../logout.php">Log out</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	<?php }

	function getFooter() {?>
<footer>
	<hr/>
	<p>Lucy was created by Ian Spence.  Version: <?php echo($GLOBALS['readonly']['version']); ?></p>
</footer>

</div>
<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
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
<h4 class="alert-heading">Access Denied</h4>
<p>Only approved administrators may access this page.</p>
			<?php
			break;
		}
		getfooter();
		die();
	}