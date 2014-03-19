<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Signup'); getNav(3); ?>
<h1>Sign up for <?php echo($GLOBALS['config']['Strings']['Main']); ?></h1>
<?php if($signup_error) { ?>
<div class="alert alert-danger">
	<?php echo($signup_error); ?>
</div>
<?php } ?>
<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label class="col-sm-2 control-label">Name:</label>
		<div class="col-sm-3">
			<input type="text" class="form-control" name="name" required/>
		</div>
	</div>
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
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-3">
			<input type="submit" name="submit" value="Signup" class="btn btn-primary"/>
		</div>
	</div>
</form>
<?php getFooter(); ?>