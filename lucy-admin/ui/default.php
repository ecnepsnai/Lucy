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
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="#">Lucy Administration</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li <?php if($pageIndex == 0){ echo('class="active"'); } ?>><a href="index.php">Dashboard</a></li>
						<li <?php if($pageIndex == 1){ echo('class="active"'); } ?>><a href="mytickets.php">My Tickets</a></li>
						<li <?php if($pageIndex == 2){ echo('class="active"'); } ?>><a href="alltickets.php">All Tickets</a></li>
						<li <?php if($pageIndex == 3){ echo('class="active"'); } ?>><a href="users.php">Users</a></li>
						<li <?php if($pageIndex == 4){ echo('class="active"'); } ?>><a href="settings.php">Settings</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
	<?php }

	function getFooter() {?>
<footer>
	<hr/>
	<p>Lucy was created by Ian Spence.  Version: <?php echo($GLOBALS['readonly']['version']); ?></p>
</footer>

</div>
	<?php }