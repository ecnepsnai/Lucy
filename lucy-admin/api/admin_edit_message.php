<?php
	require("../session.php");
	$error = array();

	// Requires user to be signed in
	if($usr_IsSignedIn === false){
		$error = array("code"=>401,"message"=>"Authentication required");
		goto writeDoc;
	}

	// Only the owner of the ticked or Admins may alter it.
	if($usr_Type != "Admin") {
		$error = array("code"=>403,"message"=>"Authentication failed");
		goto writeDoc;
	}

	$threadID = $_POST['threadid'];

	// Checks for missing threadID
	if(empty($threadID)){
		$error = array("code"=>400,"message"=>"thread ID is Missing");
		goto writeDoc;
	}

	$message = $_POST['message'];

	// If no message id was included.
	if(empty($_POST['message'])){
		$error = array("code"=>400,"message"=>"Message is Missing");
		goto writeDoc;
	}

	$updateid = $_POST['updateid'];

	// If no updateid id was included.
	if(empty($_POST['updateid'])){
		$error = array("code"=>400,"message"=>"UpdateID is Missing");
		goto writeDoc;
	}


	require("../cda.php");
	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);
	


	try{
		$response = $cda->update($threadID,array("Message"=>$message),array("UpdateID"=>$updateid));
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
		"message":""
	}
}
<?php }