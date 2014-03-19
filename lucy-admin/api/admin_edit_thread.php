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

	$id = $_POST['id'];

	// Checks for missing ID
	if(empty($id)){
		$error = array("code"=>400,"message"=>"thread ID is Missing");
		goto writeDoc;
	}

	// If no assignment id was included.
	if(empty($_POST['assignment'])){
		$error = array("code"=>400,"message"=>"Assignment ID is Missing");
		goto writeDoc;
	}

	$assignment = $_POST['assignment'];

	// If no status id was included.
	if(empty($_POST['status'])){
		$error = array("code"=>400,"message"=>"Status is Missing");
		goto writeDoc;
	}

	$status = $_POST['status'];


	require("../cda.php");
	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);
	


	try{
		$response = $cda->update("threads",array("assignedto"=>$assignment,"status"=>$status),array("id"=>$id));
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