<?php
	require("../session.php");
	$error = array();

	// Requires user to be signed in
	if($usr_IsSignedIn === false){
		$error = array("code"=>401,"message"=>"Authentication required");
		goto writeDoc;
	}

	// Only the owner of the ticked or Admins may alter it.
	if($_POST['ownerID'] == $usr_ID){} elseif($usr_Type == "Admin"){} else {
		$error = array("code"=>403,"message"=>"Authentication required");
		goto writeDoc;
	}

	$id = addslashes($_POST['id']);

	// Checks for missing ID
	if(empty($id)){
		$error = array("code"=>400,"message"=>"Ticket ID is Missing");
		goto writeDoc;
	}


	require("../cda.php");
	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);
	

	// If no message was included.
	if(empty($_POST['message'])){
		$error = array("code"=>400,"message"=>"Ticket Message is Missing");
		goto writeDoc;
	}
	$message = addslashes($_POST['message']);

	//Trims the message to the maximum length of MEDIUMTEXT.
	//IE and Opera don't support the maxlength attribute for textarea, so this is the fallback.
	$message = substr($message, 0, 16777216);


	// Tests to see if a screenshot was included.
	if (empty($_POST['data'])) {
		$img_hash = "";
	} elseif (isset($_POST['data']) && $GLOBALS['config']['Imgur']['Enable']) {

		// Getting the file information.
		$isFile = True;
		$pvars = array('image' => $_POST['data'], 'key' => $GLOBALS['config']['Imgur']['Key']);
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

	// Inserts the new entry into the ticket table.
	$values = array($usr_Name,$usr_Email,date("Y-m-d H:i:s"),$message,$img_hash);
	if($usr_Type == "Admin"){
		array_push($values, 'Agent');
	} else {
		array_push($values, 'Client');
	}
	try{
		$response = $cda->insert($id,array("Name","Email","Date","Message","File","From"),$values);
	} catch (Exception $e) {
		$error = array("code"=>500,"message"=>$e);
		goto writeDoc;
	}

writeDoc:
// There was an error
if($error['code'] != 0 && !empty($error['message'])){ ?>
{
	"response": {
		"code":<?php echo($error['code']); ?>,
		"message":"<?php echo($error['message']); ?>"
	}
}
<?php } else { ?>
{
	"response": {
		"code":200,
		"message":"<?php echo($img_hash); ?>"
	}
}
<?php }