<?php
	require("../session.php");
	require("../sql.php");
	require("../auth.php");
	require("default.php");

	$tf = new tfa;

	if(isset($_POST['submit'])){
		if($_POST['tf_enable'] == "on"){
			$tf_enabled = 1;
		} else {
			$tf_enabled = 0;
		}
		$tf_secret = $_POST['tf_secret'];
		$sql = "UPDATE userlist SET `tf_enable` = '" . $tf_enabled . "', `tf_secret` = '" . $tf_secret . "' WHERE `id` = " . $usr_ID . ";";
		echo($sql);
		try{
			sqlQuery($sql, True);
		} catch (Exception $e) {
			die($e);
		}
		$changes_Saved = True;
	}

	$sql = "SELECT * FROM userlist WHERE id = '" . $usr_ID . "'";
	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		die($e);
	}

	// User has enabled Two-Step but no secret is created.
	if($user['tf_enable'] && $user['tf_secret'] != ""){
		$tfa_secret = $user['tf_secret'];
	}

	getHeader("Two-Factor Authentication");
	getNav(999);
?>
<?php if($changes_Saved) { ?>
<div class="notice">
	<strong>Values Saved</strong>
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
		<?php if($user['tf_secret'] == "") { echo("createSecret();"); } ?>
	} else {
		$("#info").fadeOut("Fast"); 
	}
}
</script>
<div class="hero-unit">
	<h1>Two-Factor Authentication.</h1>
	<p>Take your account security to the next level by requiring a time-sensitive PIN generated on your phone as well as your normal username and password.</p>
</div>
<form class="form-horizontal" method="post">
	<h2>Authentication Settings</h2>
	<div class="control-group">
		<label class="control-label">Enable 2-Factor Login:</label>
		<div class="controls">
			<input id="tf_enb_cb" type="checkbox" name="tf_enable" <?php if($user['tf_enable']){echo('checked="checked"');} ?> title="Two-Factor authentication requires you to enter a code generated using your smart phone to login including your username and password.." onClick="freshInfo()"/>
		</div>
	</div>
	<div id="info" <?php if(!$user['tf_enable']){ echo('style="display:none"'); } ?>>
		<h2>Authentication Information</h2>
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
	</div>
	<input type="submit" name="submit" value="Save Changes" class="btn btn-primary"/>
</form>
<?php getFooter(); ?>