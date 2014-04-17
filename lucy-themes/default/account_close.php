<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Close Account'); getNav(2); ?>
<h1>Close Account: <?php echo($usr_Name); ?></h1>
<hr/>
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title">Are you absolutely sure?</h3>
	</div>
	<div class="panel-body">
		Deleting your account is permanent and instant.  <strong>You cannot undo this</strong>
	</div>
</div>
<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label class="col-sm-2 control-label">Your Password:</label>
		<div class="col-sm-3">
			<input type="password" class="form-control" name="password"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-3">
			<input type="submit" name="submit" value="Close Account" class="btn btn-danger"/>
		</div>
	</div>
	
</form>
<?php getFooter(); ?>