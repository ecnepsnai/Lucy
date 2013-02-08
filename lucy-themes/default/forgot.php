<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Forgot Password'); ?>
<h1>Forgot your password?</h1>
<form method="post">
	<table>
		<tr>
			<td>
				Enter your email address<br/>
				<input type="email" name="email" size="30"/>
			</td>
		</tr>
	</table>
	<div id="buttons">
		<input type="submit" name="submit" value="Request New Password"/>
	</div>
</form>
<?php getFooter(); ?>
</div>