<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Signup'); ?>
<h1>Signup for Lucy</h1>
<?php if($signup_error) {
	?>
<div class="notice" id="red">
	<?php echo($signup_error); ?>
</div>
	<?php
}
?>
<script type="text/javascript">
	function validateEmail(email) { 
		var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email);
	}
	function validateForm() {
		var x_name=document.forms["fm_signup"]["name"].value;
		var x_email=document.forms["fm_signup"]["email"].value;
		var x_password=document.forms["fm_signup"]["pwd"].value;

		if(x_name = "" || x_email == "" || x_password == "") {
			alert("A required field is blank.");
			return false;
		}
		if(!validateEmail(x_email)) {
			alert("The email address you provided was not valid.");
			return false;
		}
		return true;
	}
</script>
<form method="POST" name="fm_signup" onSubmit="return validateForm()">
	<p>Name:<br/><input type="text" name="name" class="txtglow" size="45"/> <em>This one should be easy.</em></p>
	<p>Email Address:<br/><input type="email" name="email" class="txtglow" size="45"/> <em>This is never sold or shared.</em></p>
	<p>Password:<br/><input type="password" name="pwd" class="txtglow" size="45"/> <em>Make it hard to guess.</em></p>
	<?php if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Signup']){
		if($cap_error){ ?>
		<div class="notice" id="yellow">
			<strong>Incorrect Captcha</strong> Try Again.
		</div>
		<?php }
		echo("<p>");
		echo recaptcha_get_html($GLOBALS['config']['ReCaptcha']['Public']);
		echo("</p>");
	} ?>
	<p><input type="submit" name="submit" value="Sign up for Lucy" class="btn" id="blue"/></p>
</form>
<?php getFooter(); ?>
</div>