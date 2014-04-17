<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Login'); getNav(999); ?>
<h1>Login to <?php echo($GLOBALS['config']['Strings']['Main']); ?></h1>
<?php if($login_error) { ?>
<div class="alert alert-danger">
	<strong>Incorrect Password</strong> - Try Again
</div>
<?php } ?>
<?php if(isset($_GET['notice'])){ ?>
<div class="alert alert-warning">You must login first</div>
<?php } ?>
<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label class="col-sm-2 control-label">Email:</label>
		<div class="col-sm-3">
			<input type="email" class="form-control" name="email" required/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Password:</label>
		<div class="col-sm-3">
			<input type="password" class="form-control" name="pwd" required/>
			<p class="help-block"><a href="forgot.php">I forgot my password</a></p>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-3">
			<input type="submit" name="submit" value="Login" class="btn btn-primary"/>
		</div>
	</div>
</form>
<?php getFooter(); ?>