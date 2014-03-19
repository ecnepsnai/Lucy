<?php
	function getHeader($pageTitle){ ?>
<!DOCTYPE html>
<meta charset="utf-8">
<title><?php echo($pageTitle . $GLOBALS['config']['Strings']['Separator'] . $GLOBALS['config']['Strings']['Main']); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style type="text/css">
body {
	padding-top: 1em;
}
</style>
<link rel="shortcut icon" href="lucy-themes\default\assets\img\favicon.png">
	<?php } 

	function getNav($pageIndex) { ?>
	<div class="container">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#lucy-navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php"><?php echo($GLOBALS['config']['Strings']['Main']); ?></a>
				</div>
				<div class="collapse navbar-collapse" id="lucy-navbar">
					
					<?php if($GLOBALS['usr_IsSignedIn']) { ?>
					<ul class="nav navbar-nav">
						<li <?php if($pageIndex == 0){ echo('class="active"'); } ?>><a href="index.php">My threads</a></li>
						<li <?php if($pageIndex == 1){ echo('class="active"'); } ?>><a href="new_thread.php">New thread</a></li>
					</ul>
					<?php } else { ?>
					<form class="navbar-form navbar-right" role="login" method="post" action="login.php">
						<div class="form-group">
							<input type="email" name="email" placeholder="Email Address" class="form-control"/>
						</div>
						<div class="form-group">
							<input type="password" name="pwd" placeholder="Password" class="form-control"/>
						</div>
						<button type="submit" class="btn btn-default">Log In</button>
					</form>
					<?php } ?>
					<?php if($GLOBALS['usr_IsSignedIn']) { ?>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><img style="display:inline;border-radius:2px;" src="http://www.gravatar.com/avatar/<?php echo(md5($GLOBALS['usr_Email'])); ?>?s=18&d=mm"> Hey, <?php echo($GLOBALS['usr_Name']); ?> <b class="caret"></b></a>
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
		</nav>
<?php }

	function getFooter(){ ?>
<footer>
	<hr/>
	<p><?php echo($GLOBALS['config']['Strings']['Footer']); ?></p>
</footer>
</div>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<?php }