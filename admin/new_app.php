<?php
	require("../session.php");
	if($usr_Type != "Admin"){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "error_auth.php\">Redirecting...");
	}
	if(isset($_POST['submit'])){
		require("../db_connect.php");
		$name = mysql_real_escape_string(trim($_POST['name']));
		$description = mysql_real_escape_string(trim($_POST['description']));
		$version = mysql_real_escape_string(trim($_POST['version']));
		$platform = mysql_real_escape_string(trim($_POST['platform']));
		$fileName = $_FILES['logo']['name'];
		$tempName = $_FILES['logo']['tmp_name'];
		$fileSize = $_FILES['logo']['size'];
		$fileType = $_FILES['logo']['type'];
		if(empty($name) || empty($description) || empty($version) || empty($platform)){
			die("Missing Value. Try again.");
		}
		$sql = "INSERT INTO applist (name, description, version, platform) VALUES ('" . $name . "','" . $description . "','" . $version . "','" . $platform . "');";
		$request = mysql_query($sql);
		if(!$request){
			require("../error_db.php");
		}
		$fileupload = move_uploaded_file($tempName, "../img/apps/" . mysql_insert_id() . ".jpg");
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "app.php?id=" . mysql_insert_id() . "\">Redirecting...");
	}
?>
<!doctype html>
<title>New App<?php echo(TITLE_SEPARATOR . TITLE_MAIN) ?></title>
<link rel="stylesheet" href="../img/loader.css">
<link href="../img/styles.css" rel="stylesheet" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<div id="wrapper">
<?php require("../mdl_header.php"); ?>
<div id="content">
<h2>Create a new application</h2>
<form method="POST" name="fm_ticket" enctype="multipart/form-data">
	<p>
		Application Name:<br/>
		<input type="text" name="name" maxlength="25" class="txtglow" size="50"/>
	</p>
	<p>
		Description of the application<br/>
		<textarea name="description" rows="10" cols="75" class="txtglow" maxlength="16777216"></textarea>
	</p>
	<p>
		Application Version:<br/>
		<input type="text" name="version" maxlength="20" class="txtglow" />
	</p>
	<p>
		Application Platform:<br/>
		<input type="text" name="platform" maxlength="20" class="txtglow" placeholder="PC, OSX, iOS..." size="50"/>
	</p>
	<p>
		Include a screenshot? (<em>Optional</em> - <a href="help_screenshots.php">Help</a>)<br/>
		<input type="file" name="logo" />
	</p>
	<p><input type="submit" name="submit" value="Create Application" class="btn" id="blue"/></p>
</form>
</div>
<?php require("../mdl_footer.php"); ?>
</div>