<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Forgot Password');?>
<style type="text/css">
.row{
	padding-top: 1em;
}
</style>
<?php getNav(999);

if($status == 1){?>
<div class="alert alert-success"><strong>Check your email</strong> - We just sent a password reset link your way!</div>
<?php } else if($status == 2){?>
<div class="alert alert-success"><strong>Check your email</strong> - S new password has been mailed to you.</div>
<?php } else if($status == 3){?>
<div class="alert alert-warning"><strong>Nothing found</strong> - No user was found with that email address.</div>
<?php } else if($status == 4){?>
<div class="alert alert-warning"><strong>Expired or Invalid Reset Tokens</strong></div>
<?php }

?>
<h1>Forgot your password?</h1>
<form class="form-horizontal" role="form" method="post">
	<div class="form-group">
		<label class="col-sm-2 control-label">Email:</label>
		<div class="col-sm-3">
			<input type="email" class="form-control" name="email" required/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-3">
			<input type="submit" name="submit" value="Request New Password" class="btn btn-primary"/>
		</div>
	</div>
</form>
<?php getFooter(); ?>