<?php
	function getHeader($pageTitle){ ?>
<!DOCTYPE html>
<meta charset="utf-8">
<title><?php echo($pageTitle . $GLOBALS['config']['Strings']['Separator'] . $GLOBALS['config']['Strings']['Main']); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="lucy-themes\default\assets\css\bootstrap.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="lucy-themes\default\assets\js\bootstrap.min.js"></script>
<style type="text/css">
body {
	padding-top: 60px;
	padding-bottom: 40px;
}
</style>
<link rel="shortcut icon" href="lucy-themes\default\assets\img\favicon.png">
	<?php } 

	function getNav($pageIndex) { ?>
	<div class="navbar navbar-fixed-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container">
				<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="brand" href="index.php">Lucy</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
					<?php if($GLOBALS['usr_IsSignedIn']) { ?>
						<li <?php if($pageIndex == 0){ echo('class="active"'); } ?>><a href="index.php">My Tickets</a></li>
						<li <?php if($pageIndex == 1){ echo('class="active"'); } ?>><a href="new_ticket.php">New Ticket</a></li>
					<?php } else { ?>
						<li <?php if($pageIndex == 3){ echo('class="active"'); } ?>><a href="signup.php">Sign Up</a></li>
						<li <?php if($pageIndex == 1){ echo('class="active"'); } ?>><a href="new_ticket.php">New Ticket</a></li>
					<?php } ?>
					</ul>
					<?php if($GLOBALS['usr_IsSignedIn']) { ?>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Hey, <?php echo($GLOBALS['usr_Name']); ?> <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="profile.php">My Profile</a></li>
								<?php if($GLOBALS['usr_Type'] == "Admin" || $GLOBALS['usr_Type'] == "Agent") { ?><li><a href="lucy-admin/ui/">Lucy Administration</a></li><?php } ?>
								<li class="divider"></li>
								<li><a href="logout.php">Log out</a></li>
							</ul>
						</li>
					</ul>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
<?php }

	function getFooter(){ ?>
<footer style="text-align:center">
	<hr/>
	<p><?php echo($GLOBALS['config']['Strings']['Footer']); ?></p>
</footer>
</div>
<?php }