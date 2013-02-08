<?php
	function getHeader($pageTitle){ ?>
<!doctype html>
<meta charset="utf-8">
<!--[if lt IE 9]>
<script src="assets/js/html5shiv.js"></script>
<![endif]-->
<title><?php echo($pageTitle); ?> — Lucy</title>
<link rel="stylesheet" href="<?php echo($GLOBALS['config']['Domain']); ?>lucy-themes/default/assets/img/loader.css">
<link rel="stylesheet" href="css/styles.css">
<script src="//code.jquery.com/jquery-1.9.0.js"></script>
<script src="//code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<link rel="shortcut icon" href="<?php echo($GLOBALS['config']['Domain']); ?>lucy-themes/default/assets/img/icon.png">
<div id="wrapper">
	<header>
		<p id="left">Lucy Administration</p>
		<p id="right">Hey there, <?php echo($GLOBALS['usr_Name']); ?>.</p>
	</header>
	<div id="container">
	<?php }

	function getSidebar($pageIndex){ ?>
<aside>
	<ul>
		<li <?php if($pageIndex == 0){ echo('id="current"'); } ?>><a href="index.php">Dashboard</a></li>
		<li <?php if($pageIndex == 1){ echo('id="current"'); } ?>><a href="tickets.php">Tickets</a></li>
		<li <?php if($pageIndex == 2){ echo('id="current"'); } ?>><a href="users.php">Users</a></li>
		<li <?php if($pageIndex == 3){ echo('id="current"'); } ?>><a href="settings.php">Settings</a></li>
	</ul>
</aside>
	<?php }

	function getFooter(){ ?>
<footer>
	Lucy was created by Ian Spence.  Version: <?php echo($GLOBALS['readonly']['version']); ?>
</footer>
	<?php }