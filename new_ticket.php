<?php
	require("session.php");
	if(!$usr_IsSignedIn){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?rdirect=new_ticket.php&notice=login\">Redirecting...");
	}
	require("db_connect.php");
	if(isset($_POST['submit'])){
		$ticketid = "HP_" . rand(0, 9) . chr(97 + mt_rand(0, 25)) . rand(1000, 9999);
		$application = mysql_real_escape_string($_POST['app']);
		$version = mysql_real_escape_string($_POST['version']);
		$os = mysql_real_escape_string($_POST['os']);
		$message = mysql_real_escape_string($_POST['message']);
		$date = date("Y-m-d H:i:s"); 
		$isFile = False;
		$filename = $_FILES['screenshot']['tmp_name'];

		//Trims the message to the maximum length of MEDIUMTEXT.
		//IE and Opera don't support the maxlength attribute for textarea, so this is the fallback.
		$message = substr($message, 0, 16777216);

		//get files - if any //
		if($filename != "") {
			$isFile = True;
			$handle = fopen($filename, "r");
			$data = fread($handle, filesize($filename));
			$pvars = array('image' => base64_encode($data), 'key' => API_IMGUR);
			$timeout = 30;
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://api.imgur.com/2/upload.json');
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
			$json = curl_exec($curl);
			curl_close ($curl);
			$data = json_decode($json,true);
			$img_hash = $data["upload"]["image"]["hash"];
		} else {
			$img_hash = "";
		}
		$sql = "INSERT INTO ticketlist (id, name, email, application, version, os, status, subject) VALUES ('" . $ticketid . "','" . $usr_Name . "','" . $usr_Email . "','" . $application . "', '" . $version . "', 'Open', " . $os . "', '" . substr($message, 0, 50) . "')";
		$request = mysql_query($sql);
		if(!$request){
			require("error_db.php");
		}
		$sql = "CREATE  TABLE `" . $ticketid . "` (`UpdateID` INT NOT NULL AUTO_INCREMENT ,  `From` ENUM('Client','Agent') NULL ,  
		`Email` VARCHAR(45) NULL ,  `Date` DATETIME NULL ,  `Message` MEDIUMTEXT NULL ,  `File` VARCHAR(25) NULL ,  PRIMARY KEY (`UpdateID`));";
		$result = mysql_query($sql);
		if (!$result){
			require("error_db.php");
		}
		$sql = "INSERT INTO `" . $ticketid . "` (`From`, `Email`, `Date`, `Message`, `File`) VALUES ('Client', '" . $usr_Email . "', '" . $date . "', '" . $message . "', '" . $img_hash . "');";
		$result = mysql_query($sql);
		if (!$result){
			require("error_db.php");
		}
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "ticket.php?id=" . $ticketid . "&notice=new\">Redirecting...");
	}

	//The list of applications is stored in the value type for the 'Application' field within ticketlist.
	//This setting can be changed within Lucy's settings.
	$sql = "DESCRIBE ticketlist application";
	$request = mysql_query($sql);
	if(!$request){
		require("error_db.php");
	}
	$response = mysql_fetch_array($request);
	$apps = $response['Type'];
	$apps = str_replace("enum(", "", $apps); //removes the "enum(" string.
	$apps = str_replace(")", "", $apps); //removes the closing enum bracket.
	$apps = str_replace("'", "", $apps); //removes the literals.
	$applist = explode(",", $apps); //explodes the string into an array.

documentCreate(TITLE_NEW_TICKET, True, False, null, null); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
<h2>Create a new ticket</h2>
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
<form method="POST" name="fm_ticket" onsubmit="return validateForm()" enctype="multipart/form-data">
	<p>
		What is the application you are using?<br/>
		<select name="app" class="txtglow">
			<option value="">Select One..</option>
			<?php
				foreach($applist as $ap){
					echo('<option value="' . $ap . '">' . $ap . '</option>');
				}
			?>
		</select>
	 </p>
	<p>
		What is the version of that application? - <a href="help_version.php">Help</a><br/>
		<input type="text" name="version" size="10" maxlength="7" class="txtglow"/>
	</p>
	<p>
		What is the operating system you are using? - <a href="help_os.php">Help</a><br/>
		<select name="os" onchange="validateOS(value)" class="txtglow">
			<option value="">Select One...</option>
			<option disabled="disabled">Microsoft Windows</option>
			<option value="WinXP">Windows XP</option>
			<option value="WinVx32">Windows Vista (32bit)</option>
			<option value="WinVx64">Windows Vista (64bit)</option>
			<option value="Win7x32">Windows 7 (32bit)</option>
			<option value="Win7x64">Windows 7 (64bit)</option>
			<option value="Win8x32">Windows 8 (RT)</option>
			<option value="Win8x64">Windows 8 (64bit / Pro)</option>
			<option disabled="disabled">Mac OS X</option>
			<option value="OSX106">Mac OS X Leopard</option>
			<option value="OSX107">Mac OS X Snow Leopard</option>
			<option value="OSX108">Mac OS X Lion</option>
			<option value="OSX109">Mac OS X Mountain Lion</option>
			<option disabled="disabled">Ubuntu</option>
			<option value="UBU10.10">Ubuntu: 10.10 - Maverick Meerkat</option>
			<option value="UBU11.04">Ubuntu: 11.04 - Natty Narwhal</option>
			<option value="UBU11.10">Ubuntu: 11.10 - Oneiric Ocelot</option>
			<option value="UBU12.04">Ubuntu: 12.04LTS - Precise Pangolin</option>
			<option value="UBU12.10">Ubuntu: 12.10 - Quantal Quetzal</option>
			<option disabled="disabled">Apple iOS</option>
			<option value="iOS4">iOS 4</option>
			<option value="iOS5">iOS 5</option>
			<option value="iOS6">iOS 6</option>
		</select>
	</p>
	<div id="osresult" class="message_client" style="display: none;"></div>
	<p>
		What is the problem?<br/>
		<textarea name="message" rows="10" cols="75" class="txtglow" placeholder="Include things like: What actions you took to cause the problem, what you expected to happene, what actually happened." maxlength="16777216"></textarea>
	</p>
	<p>
		Include a screenshot? (<em>Optional</em> - <a href="help_screenshots.php">Help</a>)<br/>
		<input type="file" name="screenshot" />
	</p>
	<p><input type="submit" name="submit" value="Create Ticket" class="btn" id="blue"/></p>
</form>
</div>
<?php writeFooter(); ?>
</div>