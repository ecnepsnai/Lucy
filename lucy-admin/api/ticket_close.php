<?php
	require("../session.php");
	$error = array();

	// Requires user to be signed in
	if($usr_IsSignedIn === false){
		$error = array("code"=>401,"message"=>"Authentication required. Provided User ID was: " . $usr_ID);
		goto writeDoc;
	}

	// Only the owner of the ticked or Admins may alter it.
	if($_POST['ownerID'] == $usr_ID){} elseif($usr_Type == "Admin"){} else {
		$error = array("code"=>403,"message"=>"Authentication required. Provided User ID was: " . $usr_ID);
		goto writeDoc;
	}

	$id = $_POST['id'];

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
	

	// Updates the master ticketlist.
	try{
		$response = $cda->update("ticketlist", array("status"=>"Closed"), array("id"=>$id));
	} catch (Exception $e){
		$error = array("code"=>500,"message"=>$e);
		goto writeDoc;
	}

	// Inserts the closed message into the ticket table.
	$values = array(date("Y-m-d H:i:s"), "CLOSED");
	if($usr_Type == "Admin"){
		array_push($values, 'Agent');
	} else {
		array_push($values, 'Client');
	}
	try{
		$response = $cda->insert($id,array("Date","Message","From"),$values);
	} catch (Exception $e){
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