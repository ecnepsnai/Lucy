<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Profile'); getNav(2); ?>
<h1><?php echo($usr_Name); ?></h1>
<hr/>
<form class="form-horizontal" role="form" method="post">
	<div class="row">
		<div class="col-md-5">
			<div class="form-group">
				<label class="col-sm-5 control-label">Current Password:</label>
				<div class="col-sm-7">
					<input type="password" class="form-control" name="pwd"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">New Password:</label>
				<div class="col-sm-7">
					<input type="password" class="form-control" name="pwd"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">New Password (Again):</label>
				<div class="col-sm-7">
					<input type="password" class="form-control" name="pwd"/>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="form-group">
				<label class="col-sm-5 control-label">Name:</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" name="name" value="<?php echo($user['name']); ?>" required/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Email:</label>
				<div class="col-sm-7">
					<input type="email" class="form-control" name="email" value="<?php echo($user['email']); ?>" required/>
					<?php if($user['verified'] == 1){ ?><p class="help-block"><span class="label label-success">Verified</span></p><?php } else { ?><p class="help-block"><span class="label label-warning">Not Verified</span> <a href="email_erify.php">Verify Email</a></p><?php } ?>
				</div>
			</div>
		</div>
	</div>
	<input type="submit" name="submit" value="Save Changes" class="btn btn-primary"/> <input type="reset" name="reset" value="Delete Account" class="btn btn-danger"/>
</form>
<?php getFooter(); ?>