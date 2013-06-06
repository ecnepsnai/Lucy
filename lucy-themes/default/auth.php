<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Login'); getNav(999); ?>
<h1>Two-Factor Authentication</h1>
<p>Please enter the PIN generated using your mobile device.</p>
<?php if($auth_error) {
	?>
<div class="alert">
	<strong>Incorrect PIN</strong> Please try again...
</div>
<?php } ?>
<form method="post" class="form-horizontal">
	<div class="control-group">
		<label class="control-label">Enter Code:</label>
		<div class="controls">
			<input type="text" name="pin" maxlength="6"/>
		</div>
	</div>
	<input type="submit" name="submit" value="Verify" class="btn btn-primary"/>
</form>
<?php getFooter(); ?>