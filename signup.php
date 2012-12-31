<?php
require("assets/lib/session.php");
require("assets/lib/sql.php");

// Requires the captcha library if reCAP is enabled.
if(reCAP_enable){
	require("assets/lib/recaptchalib.php");
}

// If there is already a user signed in, redirect them away from this page.
if($usr_IsSignedIn){
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "dash.php\">Redirecting...");
}

// If the user chose to signup.
if(isset($_POST['submit'])){

	// Validates the reCAPTICHA challenge if enabled.
	if(reCAP_enable){
		$resp = recaptcha_check_answer (reCAP_private, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			$cap_error = True;
			goto writeDOC;
		}
	}

	// Getting the name and email.
	$raw_name = trim($_POST['name']);
	$raw_email = trim($_POST['email']);

	// Validating the inputs.
	if(empty($raw_name) || empty($raw_email) || empty($_POST['pwd'])){
		$error = "<strong>Missing Information</strong><br/>All of the fields are required.";
		goto writeDOC;
	}

	require("assets/lib/validEmail.php");
	if(!validEmail($raw_email)){
		$error = "<strong>Invalid Email</strong><br/>The email address you provided was not valid.";
		goto writeDOC;
	}

	// Generating a random salt used for encryption.
	$salt = mt_rand(10, 99);

	// Encrypting the password.
	$hashed_password = md5($salt . md5($_POST['pwd']));
	$inp_name = addslashes($raw_name);
	$inp_email = addslashes($raw_email);

	// Creating the SQL statment.
	// We hard-code in the user as a Client user with the assumption that there is already an admin.
	$sql = "INSERT INTO  userlist (type, name, email, password, date_registered, salt) VALUES ('Client',  '" . $inp_name . "',  '" . $inp_email . "',  '" . $hashed_password . "',  '" . date("Y-m-d") . "', '". $salt ."');";
	try{
		sqlQuery($sql, True);
	} catch (Exception $e){
		require("error_db.php");
	}

	// Gets the id from the database.
	$sql = "SELECT id FROM userlist WHERE email = '" . $inp_email . "'";
	try{
		$user = sqlQuery($sql, True);
	} catch (Exception $e){
		require("error_db.php");
	}

	// Opens the session for the user.
	session_start();
	$_SESSION['id'] = $user['id'];
	$_SESSION['name'] = $inp_name;
	// Like before, we hard-code all users as Clients when signing up.
	$_SESSION['type'] = 'Client';
	$_SESSION['email'] = $inp_email;
	$_SESSION['LAST_ACTIVITY'] = time();


	// Dies is successful.
	die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "new_ticket.php\">Redirecting...");
}
writeDOC:
documentCreate(TITLE_SIGNUP, True); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
<?php if(isset($error)){
echo('<div class="notice" id="red">' . $error . '</div>');
} ?>
<h1>Create a new account</h1>
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
	<p>Password:<br/><input type="password" name="pwd" class="txtglow" size="45"/> <em>Choose something easy to remember, but hard to guess.</em></p>
	<?php if(reCAP_enable){
		if($cap_error){ ?>
		<div class="notice" id="yellow">
			<strong>Incorrect Captcha</strong> Try Again.
		</div>
		<?php }
		echo("<p>");
		echo recaptcha_get_html(reCAP_public);
		echo("</p>");
	} ?>
	<p><input type="submit" name="submit" value="Sign up for Lucy" class="btn" id="blue"/></p>
</form>
</div>
<?php writeFooter(); ?>
</div>