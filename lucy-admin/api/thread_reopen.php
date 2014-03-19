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
		$error = array("code"=>400,"message"=>"thread ID is Missing");
		goto writeDoc;
	}


	require("../cda.php");
	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// Inserts the new entry into the thread table.
	$values = array($usr_Name,$usr_Email,date("Y-m-d H:i:s"),'OPEN');
	if($usr_Type == "Admin"){
		array_push($values, 'Agent');
	} else {
		array_push($values, 'Client');
	}
	
	$get_response = null;

	try{
		$get_response = $cda->select(array('data'),'threads',array('id'=>$id));
	} catch (Exception $e){
		$error  = $e;
		goto writeDoc;
	}
	$json = json_decode($get_response['data']['data']);


	if($usr_Type !== "Admin"){
		if($json->messages[count($json->messages) - 1]->body == "SPAM"){
			$error = array("code"=>403,"message"=>"This thread has been marked as spam and cannot be reopened.");
			goto writeDoc;
		}
		if($json->messages[count($json->messages) - 1]->body == "CLOSED" && $json->messages[count($json->messages) - 1]->owner->name !== $usr_Name){
			$error = array("code"=>403,"message"=>"You can only reopen threads you closed.");
			goto writeDoc;
		}
	}

	$messageData = array("id"=>count($json->messages) + 1,"from"=>array("id"=>intval($usr_ID), "name"=>$usr_Name, "email"=>$usr_Email),"body"=>'OPEN',"image"=>null);

	array_push($json->messages, $messageData);

	$put_response = null;
	try{
		$put_response = $cda->update('threads',array('status'=>'Active','data'=>json_encode($json)),array('id'=>$id));
	}  catch (Exception $e){
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