<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));
include('default.php');

getHeader('Login'); getNav(999);
		if($_GET['notice'] == "login"){
			?>
			<div class="alert">
				<strong>You need to be signed in to do that.</strong> Don't have an account? <a href="signup.php">Create one here</a>.
			</div>
			<?php
		} elseif($_GET['notice'] == "welcome"){
			?>
			<div class="alert alert-success">
				<strong>Welcome to Lucy!</strong> All of the required settings are configured.  Log in and go to settings to fine-tune Lucy to your liking!</a>.
			</div>
			<?php
		}
	?>
<h1>Login to Lucy</h1>
<?php if($login_error) {
	?>
<div class="alert">
	<strong>Incorrect Password</strong> Please try again...
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
<form method="POST" name="fm_login" onSubmit="return validateForm()" class="form-horizontal">
	<div class="control-group">
		<label class="control-label">Email Address:</label>
		<div class="controls">
			<input type="email" name="email" size="45"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Password:</label>
		<div class="controls">
			<input type="password" name="pwd" size="45"/>
		</div>
	</div>
	<?php if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Login']){
		if($cap_error){ ?>
		<div class="notice" id="yellow">
			<strong>Incorrect Captcha</strong> Try Again.
		</div>
		<?php }
		echo recaptcha_get_html($GLOBALS['config']['ReCaptcha']['Public']);
	} ?>
	<input type="submit" name="submit" value="Log in" class="btn btn-primary"/> <a href="signup.php">Need an account?</a> | <a href="forgot.php">Forgot your password?</a>
</form>
<?php getFooter(); ?>