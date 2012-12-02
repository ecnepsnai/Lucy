<?php
	require("session.php");
	if($usr_IsSignedIn != True) {
		?>
			<!doctype html>
			<title>Error | Lucy</title>
			<link rel="stylesheet" href="img/loader.css">
			<link href="img/styles.css" rel="stylesheet" type="text/css">
			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
			<div id="wrapper">
			<?php require("mdl_header.php"); ?>
			<div id="content">
			<div class="notice" id="red">
				<strong>Authentication Error</strong><br/>
				You do not have permission to view this page.
			</div>
			</div>
			<?php require("mdl_footer.php"); ?>
			</div>
		<?php die();
	}
	$id = $usr_ID;
	require("db_connect.php");
	$id = mysql_real_escape_string($id);
	if(isset($_POST['submit'])){
		$name = mysql_real_escape_string(trim($_POST['name']));
		$email = mysql_real_escape_string(trim($_POST['email']));
		$specs = mysql_real_escape_string(trim($_POST['specs']));
		$sql = "UPDATE userlist SET name = '" . $name . "', email = '" . $email . "', rig_specs = '" . $specs . "' WHERE id = '" . $id . "';";
		$request = mysql_query($sql);
		if(!$request){
			require("error_db.php");
		}
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "profile.php?id=" . $id . "&change=save\">Redirecting...");
	}
	$sql = "SELECT * FROM userlist WHERE id ='" . $id . "';";
	$request = mysql_query($sql);
	if(mysql_num_rows($request) == 0){
		?>
		<!doctype html>
		<title>Error | Lucy</title>
		<link rel="stylesheet" href="img/loader.css">
		<link href="img/styles.css" rel="stylesheet" type="text/css">
		<script src="js/jquery.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<div id="wrapper">
		<?php require("mdl_header.php"); ?>
		<div id="content">
		<h2>User Not Found</h2>
		<p>The UserID you specified does not exist.</p>
		</div>
		<?php require("mdl_footer.php"); ?>
		</div>
		<?php
		die();
	}
	$user = mysql_fetch_array($request);
?>
<!doctype html>
<title>Edit User: <?php echo($user['name']); ?> | Lucy</title>
<link rel="stylesheet" href="img/loader.css">
<link href="img/styles.css" rel="stylesheet" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<div id="wrapper">
<?php require("mdl_header.php"); ?>
<div id="content">
<h2>Edit User: <?php echo($user['name']); ?></h2>
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
	<p>Name:<br/><input type="text" name="name" class="txtglow" size="45" value="<?php echo($user['name']); ?>"/></p>
	<p>Email Address:<br/><input type="email" name="email" class="txtglow" size="45" value="<?php echo($user['email']); ?>"/></p>
	<p>Your computers specifications: <a href="help_specs.php">Help</a><br/><textarea name="specs" class="txtglow" rows="10" cols="75" placeholder="Example: Windows 8 64bit.  AMD-FX8150, 16GB of Memory, 2TB of Hard-Drive space."><?php echo($user['rig_specs']); ?></textarea>
	<p><input type="submit" name="submit" value="Save Changes" class="btn" id="blue"/></p>
</form>
</div>
<?php require("mdl_footer.php"); ?>
</div>