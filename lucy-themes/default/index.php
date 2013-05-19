<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Welcome'); ?>
<h1 style="text-align:center">Sometimes you just need a little help.</h1>
<div id="index-wrapper">
<div id="index-ticket">
<h2>First time here?</h2>
<form action="new_ticket.php" method="GET" name="fm_ticket" onSubmit="return validateTicket()">
	<p>What's your name?:<br/><input type="text" name="n"/></p>
	<p>And your Email Address:<br/><input type="email" name="e"/></p>
	<p>And chose a password:<br/><input type="password" name="p"/></p>
	<p><input type="submit" name="submit" value="Next"/> We'll finish this on the next page.</p>
</form>
</div>
<div id="index-login">
<h2>Already with us?</h2>
<form action="login.php" method="POST" name="fm_login" onSubmit="return validateLogin()">
	<p>Email Address:<br/><input type="email" name="email"/></p>
	<p>Password:<br/><input type="password" name="pwd"/></p>
	<?php if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Login']){
		if($cap_error){ ?>
		<div class="notice" id="yellow">
			<strong>Incorrect Captcha</strong> Try Again.
		</div>
		<?php }
		echo("<p>");
		echo recaptcha_get_html($GLOBALS['config']['ReCaptcha']['Public']);
		echo("</p>");
	} ?>
	<p><input type="submit" name="submit" value="Log in"/> <a href="forgot.php">Forgot your password?</a></p>
</form>
</div>
</div>
<?php getFooter(); ?>