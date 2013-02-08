<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Login'); 
		if($_GET['notice'] == "login"){
			?>
			<div class="notice" id="yellow">
				<strong>You need to be signed in to do that.</strong><br/>
				Don't have an account? <a href="signup.php">Create one here</a>.
			</div>
			<?php
		}
	?>
<h1>Login</h1>
<?php if($login_error) {
	?>
<div class="notice" id="red">
	<strong>Incorrect Password</strong><br/>
	Please try again...
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
		var x_email=document.forms["fm_login"]["email"].value;
		var x_password=document.forms["fm_login"]["pwd"].value;

		if(x_email == "" || x_password == "") {
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
<form method="POST" name="fm_login" onSubmit="return validateForm()">
	<p>Email Address:<br/><input type="email" name="email" class="txtglow" size="45"/></p>
	<p>Password:<br/><input type="password" name="pwd" class="txtglow" size="45"/></p>
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
	<a href="signup.php">Need an account?</a> | <a href="forgot.php">Forgot your password?</a>
	<p><input type="submit" name="submit" value="Log in"/></p>
</form>
<?php getFooter(); ?>
</div>