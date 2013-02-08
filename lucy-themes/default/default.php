<?php
	function getHeader($pageTitle){ ?>
<!doctype html>
<meta charset="utf-8">
<!--[if lt IE 9]>
<script src="assets/js/html5shiv.js"></script>
<![endif]-->
<title><?php echo($pageTitle . ' — Lucy'); ?></title>
<link rel="stylesheet" href="<?php echo($GLOBALS['config']['Domain']); ?>lucy-themes/default/assets/img/loader.css">
<link rel="stylesheet" href="<?php echo($GLOBALS['config']['Domain']); ?>lucy-themes/default/assets/img/styles.css">
<script src="//code.jquery.com/jquery-1.9.0.js"></script>
<script src="//code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<link rel="shortcut icon" href="<?php echo($GLOBALS['config']['Domain']); ?>lucy-themes/default/assets/img/icon.png">
<div id="wrapper">
	<header>
		<div id="title">
			<a href="<?php echo($GLOBALS['config']['Domain']); ?>"><img src="<?php echo($GLOBALS['config']['Domain']); ?>lucy-themes/default/assets/img/logo.png" alt="Lucy"/></a>
		</div>
		<nav>
			<ul>
				<li><a href="<?php echo($GLOBALS['config']['Domain']); ?>new_ticket.php">Create a Ticket</a></li>
				<?php if($GLOBALS['usr_IsSignedIn']) { ?>
				<li><a href="<?php echo($GLOBALS['config']['Domain']); ?>profile.php">My Profile</a></li>
				<li><a href="<?php echo($GLOBALS['config']['Domain']); ?>dash.php">Checkup on your Tickets</a></li>
				<?php } else {?> 
				<li><a href="<?php echo($GLOBALS['config']['Domain']); ?>login.php">Login</a></li>
				<?php } ?>
			</ul>
		</nav>
	</header>
<?php }

	function getFooter(){ ?>
	<footer>
		<p class="left">Copyright &copy; Ian Spence 2013.</p>
		<p class="right">Powered by the <a href="//ianspence.com/lucy">Lucy Framework</a>.</p>
	</footer>
</div>
<?php }