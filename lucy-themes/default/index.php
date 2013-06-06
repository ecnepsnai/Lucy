<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Welcome'); getNav(999); ?>
<div class="hero-unit">
	<h1>Sometimes you just need a little help.</h1>
	<p>That's why we're here.  Lucy is an easy to use Support System built using PHP that is both easy to setup and easy to use.</p>
</div>
<hr/>
<div class="row-fluid">
	<div class="span6" style="text-align:right">
		<h2>First time here?</h2>
		<form action="new_ticket.php" method="GET" name="fm_ticket" class="form-horizontal">
			<input type="text" name="n" placeholder="Whats your Name?" class="input-block-level" style="margin-bottom:15px"/>
			<input type="email" name="e" placeholder="And your Email Address" class="input-block-level" style="margin-bottom:15px"/>
			<input type="password" name="p" placeholder="Chose a password" class="input-block-level" style="margin-bottom:15px"/>
			We'll continue on the next page <input type="submit" name="submit" value="Continue" class="btn"/>
		</form>
	</div>
	<div class="span6">
		<h2>Already with us?</h2>
		<form action="login.php" method="POST" name="fm_login" class="form-horizontal">
			<input type="email" name="email" placeholder="Email Address" class="input-block-level" style="margin-bottom:15px"/>
			<input type="password" name="pwd" placeholder="Password" class="input-block-level" style="margin-bottom:15px"/>
			<input type="submit" name="submit" value="Log in" class="btn"/> <a href="forgot.php">Forgot your password?</a>
		</form>
	</div>
</div>
<?php getFooter(); ?>