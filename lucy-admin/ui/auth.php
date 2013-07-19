<?php
	require("../session.php");
	require("../cda.php");
	require("../auth.php");
	require("default.php");

	$tf = new tfa;

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	if(isset($_POST['submit'])){
		// Ask for user salt first, then verify, THEN get rest of data
		try {
			$response = $cda->select(array("salt"),"userlist",array("email"=>$usr_Email));
		} catch (Exception $e) {
			die($e);
		}
		$pw = $response['data'];
		$pw_hash = md5($pw['salt'] . md5(trim($_POST['tf_password'])));

		// Preparing the second sql request.
		try {
			$response = $cda->select(array("id"),"userlist",array("email"=>$usr_Email,"password"=>$pw_hash));
		} catch (Exception $e) {
			die($e);
		}
		$user = $response['data'];
		if(empty($user['id'])){
			$login_error = True;
		} else {
			if($_POST['tf_enable'] == "on"){
				$tf_enabled = 1;
			} else {
				$tf_enabled = 0;
			}
			$tf_secret = $_POST['tf_secret'];

			try{
				$response = $cda->update("userlist", array("tf_enable"=>$tf_enabled,"tf_secret"=>$tf_secret),array("id"=>$usr_ID));
			} catch (Exception $e) {
				die($e);
			}
			$changes_Saved = True;
		}
	}

	try {
		$response = $cda->select(array("tf_enable","tf_secret"),"userlist",array("id"=>$usr_ID));
	} catch (Exception $e) {
		die($e);
	}
	$user = $response['data'];

	// User has enabled Two-Step but no secret is created.
	if($user['tf_enable'] && $user['tf_secret'] != ""){
		$tfa_secret = $user['tf_secret'];
	}

	getHeader("Two-Factor Authentication");
	getNav(999);
?>
<?php if($changes_Saved) { ?>
<div class="alert alert-info">
	<strong>Values Saved</strong>
</div>
<?php } if($login_error) { ?>
<div class="alert alert-error">
	<strong>Incorrect Password.  Changes not saved.</strong>
</div>
<?php } ?>
<script type="text/javascript">
var secretCreated = false;
function createSecret(){
	if(secretCreated){
		return;
	}
	var secret = "";
	var dict = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for( var i=0; i < 16; i++ ) {
        secret += dict.charAt(Math.floor(Math.random() * dict.length));
    }
    document.getElementById("tf_pin_tb").value = secret;
    var encodeQR = "otpauth://totp/Lucy?secret=" + secret
    document.getElementById("tf_qr_img").src = "https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=" + encodeURIComponent(encodeQR);
    secretCreated = true;
}

function freshInfo(){ 
	if(document.getElementById("tf_enb_cb").checked == true){
		$("#info").fadeIn("Fast");
		createSecret();
	} else {
		$("#info").fadeOut("Fast");
		document.getElementById("tf_pin_tb").value = ""; 
	}
}

function enableSave(){
	if(document.getElementById("tf_password").value != ""){
		$("#submitbtn").removeClass("btn").addClass("btn btn-primary");
		$("#submitbtn").prop('disabled', false);
		$("#submitbtn").val("Save Changes");
	} else {
		$("#submitbtn").removeClass("btn btn-primary").addClass("btn");
		$("#submitbtn").prop('disabled', true);
		$("#submitbtn").val("Enter Password First...");
	}
}
</script>
<div class="hero-unit">
	<h1>Two-Factor Authentication.</h1>
	<p>Take your account security to the next level by requiring a time-sensitive PIN generated on your phone as well as your normal username and password.</p>
</div>
<form class="form-horizontal" method="post">
	<div class="control-group">
		<label class="control-label">Your password:</label>
		<div class="controls">
			<input type="password" id="tf_password" name="tf_password" oninput="enableSave()"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Enable 2-Factor Login:</label>
		<div class="controls">
			<input id="tf_enb_cb" type="checkbox" name="tf_enable" <?php if($user['tf_enable']){echo('checked="checked"');} ?> title="Two-Factor authentication requires you to enter a code generated using your smart phone to login including your username and password.." onClick="freshInfo()"/>
		</div>
	</div>
	<div id="info" <?php if(!$user['tf_enable']){ echo('style="display:none"'); } ?>>
		<hr/>
		<div class="control-group">
			<label class="control-label">Secret:</label>
			<div class="controls">
				<input type="text" id="tf_pin_tb" name="tf_secret" maxlength="32" value="<?php echo($tfa_secret); ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">QR Code:</label>
			<div class="controls">
				<img id="tf_qr_img" <?php if($user['tf_enable']){echo('src="https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=' . urlencode('otpauth://totp/Lucy?secret='.$user['tf_secret']) . '"');} ?> height="200" width="200" alt="QR Code" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Code Generating Apps:</label>
			<div class="controls">
				<ul>
					<li><a href="https://itunes.apple.com/ca/app/authy/id494168017?mt=8">Authy (iOS)</a></li>
					<li><a href="https://itunes.apple.com/ca/app/google-authenticator/id388497605?mt=8">Google Authenticator (iOS)</a>
					<li><a href="https://play.google.com/store/apps/details?id=com.authy.authy">Authy (Android)</a></li>
					<li><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Google Authenticator (Android)</a></li>
					<li><a href="http://www.windowsphone.com/en-ca/store/app/authenticator/e7994dbc-2336-4950-91ba-ca22d653759b">Authenticator (Windows Phone)</a></li>
					<li><a href="http://appworld.blackberry.com/webstore/content/22517879/">Authomator (BlackBerry 10)</a></li>
				</ul>
			</div>
		</div>
	</div>
	<input type="submit" name="submit" value="Enter Password First..." class="btn" id="submitbtn" disabled/>
</form>
<?php getFooter(); ?>