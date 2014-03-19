<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Welcome'); getNav(999); ?>
<?php if($_GET['notice'] == 'readonly'){ ?>
<div class="alert alert-danger">
	<strong>Unable to Login - </strong> Lucy is in Read-Only Mode, only approved administrators may log in.
</div>
<?php } ?>
<div class="row">
	<div class="col-md-6">
		<h1><?php echo($GLOBALS['config']['Strings']['Home']['Title']); ?></h1>
		<p><?php echo($GLOBALS['config']['Strings']['Home']['Slogan']); ?></p>
	</div>
	<div class="col-md-6">
		<h2>Sign Up</h2>
		<form action="new_thread.php" method="GET" name="fm_thread" class="form-horizontal">
			<div class="form-group">
				<label for="n">What's your Name?</label>
				<input type="text" name="n" placeholder="Jimmy Jones" class="form-control"/>
			</div>
			<div class="form-group">
				<label for="e">And your Email address</label>
				<input type="email" name="e" placeholder="jim@jones.com" class="form-control"/>
			</div>
			<div class="form-group">
				<input type="submit" name="submit" value="Continue" class="btn btn-default"/> We'll finish on the next page
			</div>
		</form>
	</div>
</div>
<?php getFooter(); ?>