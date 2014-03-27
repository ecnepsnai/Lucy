<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Login'); getNav(999); ?>
<div class="row">
	<div class="col-md-5">
		<h1>Two-Factor Authentication</h1>
		<p>Please enter the PIN generated using your mobile device.</p>
	</div>
	<div class="col-md-7" id="pin">
		<form method="post" class="form-horizontal">
			<div class="form-group">
				<label for="n">Enter Code</label>
				<input type="text" name="pin" maxlength="6" class="form-control"/>
			</div>
			<div class="form-group">
				<input type="submit" name="submit" value="Login" class="btn btn-primary"/>
				<a href="#" class="btn btn-default" id="nowork">Help</a>
			</div>
		</form>
	</div>
	<div class="col-md-7" id="backup" style="display:none">
		<form method="post" class="form-horizontal">
			<div class="form-group">
				<label for="n">Enter Password</label>
				<input type="password" name="password" class="form-control"/>
			</div>
			<div class="form-group">
				<label for="n">Enter Backup Code</label>
				<input type="text" name="backup" maxlength="6" class="form-control"/>
			</div>
			<div class="form-group">
				<input type="submit" name="submit" value="Recover Account" class="btn btn-primary"/>
			</div>
		</form>
	</div>
</div>
<?php getFooter(); ?>
<script type="text/javascript">
$("#nowork").bind('click',function(){
	$("#pin").slideUp('fast');
	$("#backup").slideDown('fast');
});
</script>