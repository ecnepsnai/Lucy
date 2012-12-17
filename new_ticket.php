<?php
	require("session.php");

	// This page requires a user to be signed in.
	if(!$usr_IsSignedIn){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "login.php?rdirect=new_ticket.php&notice=login\">Redirecting...");
	}


	require("sql.php");

	// User submitted a new ticket.
	if(isset($_POST['submit'])){

		// Getting & Setting the ticket information.
		$ticketid = "HP_" . rand(0, 9) . chr(97 + mt_rand(0, 25)) . rand(1000, 9999);
		$application = addslashes($_POST['app']);
		$version = addslashes($_POST['version']);
		$os = addslashes($_POST['os']);
		$message = addslashes($_POST['message']);
		$date = date("Y-m-d H:i:s"); 
		$isFile = False;
		$filename = $_FILES['screenshot']['tmp_name'];

		// Trims the message to the maximum length of MEDIUMTEXT.
		// IE and Opera don't support the maxlength attribute for textarea, so this is the fallback.
		$message = substr($message, 0, 16777216);


		// Tests to see if a screenshot was included.
		if (empty($filename)) {
			$img_hash = "";
		} else {

			// Getting the file information.
			$isFile = True;
			$handle = fopen($filename, "r");
			$data = fread($handle, filesize($filename));
			$pvars = array('image' => base64_encode($data), 'key' => API_IMGUR);
			$timeout = 30;

			// Setting up the cUrl uploader.
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'http://api.imgur.com/2/upload.json');
			curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);

			// Uploading to Imgur.
			$json = curl_exec($curl);
			curl_close ($curl);
			$data = json_decode($json,true);

			// Getting the image hash from the response.
			$img_hash = $data["upload"]["image"]["hash"];
		}

		// Inserting the ticket into the master ticket list.
		$sql = "INSERT INTO ticketlist (id, name, email, application, version, os, status, subject, date, lastreply) ";
		$sql.= "VALUES ('" . $ticketid . "','" . $usr_Name . "','" . $usr_Email . "','" . $application . "', '" . $version . "', '" . $os . "', 'Open', '" . substr($message, 0, 50) . "', '" . date("Y-m-d H:i:s") . "', 'Client')";
		try {
			sqlQuery($sql);
		} catch (Exception $e) {
			require("error_db.php");
		}

		// Creating the specific table for the ticket.
		$sql = "CREATE  TABLE `" . $ticketid . "` (`UpdateID` INT NOT NULL AUTO_INCREMENT ,  `From` ENUM('Client','Agent') NULL ,  
		`Email` VARCHAR(45) NULL ,  `Date` DATETIME NULL ,  `Message` MEDIUMTEXT NULL ,  `File` VARCHAR(25) NULL ,  PRIMARY KEY (`UpdateID`));";
		try {
			sqlQuery($sql);
		} catch (Exception $e) {
			require("error_db.php");
		}

		// Inserting information into that table.
		$sql = "INSERT INTO `" . $ticketid . "` (`From`, `Email`, `Date`, `Message`, `File`) VALUES ('Client', '" . $usr_Email . "', '" . $date . "', '" . $message . "', '" . $img_hash . "');";
		try {
			sqlQuery($sql);
		} catch (Exception $e) {
			require("error_db.php");
		}

		// Dies when complete.
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=" . SERVER_DOMAIN . "ticket.php?id=" . $ticketid . "&notice=new\">Redirecting...");
	}

	//Get the list of applications from applist
	$sql = "SELECT name FROM applist";
	try {
		$apps = sqlQuery($sql);
	} catch (Exception $e) {
		require("error_db.php");
	}

documentCreate(TITLE_NEW_TICKET, True); ?>
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
				foreach($apps as $app){
					echo('<option value="' . $app['name'] . '">' . $app['name'] . '</option>');
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
			<option value="iOS421">iOS 4.2.1</option>
			<option value="iOS511">iOS 5.1.1</option>
			<option value="iOS601">iOS 6.0.1</option>
			<option value="iOS61B3">iOS 6.1 Beta 3</option>
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