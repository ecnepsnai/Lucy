<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Login'); getNav(999);
		if($_GET['notice'] == "login"){
			?>
			<div class="alert">
				<strong>You need to be signed in to do that.</strong> Don't have an account? <a href="signup.php">Create one here</a>.
			</div>
			<?php
		} elseif($_GET['notice'] == "welcome"){
			?>
			<div class="alert alert-success">
				<strong>Welcome to Lucy!</strong> All of the required settings are configured.  Log in and go to settings to fine-tune Lucy to your liking!</a>.
			</div>
			<?php
		}
	?>
<h1>Login to <?php echo($GLOBALS['config']['Strings']['Main']); ?></h1>
<?php if($login_error) { ?>
<div class="alert alert-danger">
	<strong>Incorrect Password</strong> - Try Again
</div>
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