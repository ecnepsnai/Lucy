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
	

	// If no message was included.
	if(empty($_POST['message'])){
		$error = array("code"=>400,"message"=>"thread Message is Missing");
		goto writeDoc;
	}
	$message = $_POST['message'];

	//Trims the message to the maximum length of MEDIUMTEXT.
	//IE and Opera don't support the maxlength attribute for textarea, so this is the fallback.
	$message = substr($message, 0, 16777216);


	// Tests to see if a screenshot was included.
	if (empty($_POST['image'])) {
		// No Screenshot Included
		$img_hash = null;
	} elseif ($GLOBALS['config']['Images']['Enable'] && !empty($_POST['image'])) {
		// Screenshots Enabled and file upload was successful.
		$hash = md5(time());
		$data = strstr($_POST['image'], ',');
		$data = str_replace(',', '', $data);
		$data = base64_decode($data);
		file_put_contents('../../lucy-content/uploads/' . $hash, $data);
		$img_hash = $hash;
	}

	// Inserts the new entry into the thread table.
	$values = array($usr_Name,$usr_Email,date("Y-m-d H:i:s"),$message,$img_hash);
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

	$messageData = array("id"=>count($json->messages) + 1,"from"=>array("id"=>intval($usr_ID), "name"=>$usr_Name, "email"=>$usr_Email),"body"=>$message,"image"=>$img_hash);

	array_push($json->messages, $messageData);

	$put_response = null;
	try{
		$put_response = $cda->update('threads',array('data'=>json_encode($json)),array('id'=>$id));
	}  catch (Exception $e){
		$error  = $e;
		goto writeDoc;
	}

	// Sending out Reply Email Notice
	if($usr_Type == "Admin"){
		$owner_name = $json->messages[0]->from->name;
		$owner_email = $json->messages[0]->from->email;

		$url = 'http://' . $_SERVER['SERVER_NAME'] . str_replace('/lucy-admin/api/thread_update.php','/thread',$_SERVER['PHP_SELF']) . '?id=' . $id;

		mailer_threadUpdate($owner_name, $owner_email, $url);
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