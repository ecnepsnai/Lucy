<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Forgot Password'); getNav(999); ?>
<h1>Forgot your password?</h1>
<form method="post" class="form-horizontal">
	<div class="control-group">
		<label class="control-label">Email address:</label>
		<div class="controls">
			<input type="email" name="email" size="30"/>
		</div>
	</div>
	<input type="submit" name="submit" value="Request New Password" class="btn"/>
</form>
<?php getFooter(); ?>