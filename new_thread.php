<?php
require('lucy-admin/session.php');
$val_error = null;

if($GLOBALS['config']['ReadOnly'] == true && $user['type'] != "Admin"){
	header("location: index.php?notice=readonly");
}


$url = dirname(__FILE__) . '/lucy-config/designer.json';

$json = file_get_contents($url);
$designer = json_decode($json, true);

// User submitted a new thread.
if(isset($_POST['submit'])){
	// Requiring the CDA library.
	require("lucy-admin/cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// Validate the form if Javascript failed
	if(empty($_POST['message'])){
		$val_error = True;
		goto writeDOC;
	}
	$inp_name = null;
	$inp_email = null;

	// Getting & Setting the thread information.
	if($usr_IsSignedIn){
		$inp_name = $usr_Name;
		$inp_email = $usr_Email;
	} else {
		// Gathering the Email and Name.
		$inp_name = $_POST['name'];
		$inp_email = $_POST['email'];

		if(empty($inp_name) || empty($inp_name)){
			$val_error = True;
			goto writeDOC;
		}

		// Generating a random salt used for encryption.
		$salt = mt_rand(10, 99);

		// Encrypting the password.
		$hashed_password = md5($salt . md5($_POST['password']));

		// Creating the SQL statment.
		// We hard-code in the user as a Client user with the assumption that there is already an admin.
		try{
			$response = $cda->insert("userlist",array("type","name","email","password","date_registered","salt"),array("Client",$inp_name,$inp_email,$hashed_password,date("Y-m-d"),$salt));
		} catch (Exception $e){
			$signup_error = $e;
			goto writeDOC;
		}

		// Opens the session for the user.
		session_start();
		$_SESSION['id'] = $response['id'];
		$_SESSION['name'] = $inp_name;
		// Like before, we hard-code all users as Clients when signing up.
		$_SESSION['type'] = 'Client';
		$_SESSION['email'] = $inp_email;
		$_SESSION['LAST_ACTIVITY'] = time();
	}

	// Creating the thread ID based off on the setting
	$threadid = "";
	foreach (str_split($GLOBALS['config']['Support']['ID']) as $char) {
		if($char == "#"){
			$threadid.= rand(0, 9);
		} elseif($char == "%"){
			$threadid.= chr(97 + mt_rand(0, 25));
		} else {
			$threadid.= $char;
		}
	}
	$message = $_POST['message'];
	$date = date("Y-m-d H:i:s"); 
	$isFile = False;
	$filename = $_FILES['screenshot']['tmp_name'];


	// Tests to see if a screenshot was included.
	if (empty($filename)) {
		// No Screenshot Included
		$img_hash = null;
	} elseif ($GLOBALS['config']['Images']['Enable'] && $_FILES['screenshot']['error'] == UPLOAD_ERR_OK) {
		// Screenshots Enabled and file upload was successful.
		$hash = uniqid("", true);;
		move_uploaded_file($filename,'lucy-content/uploads/' . $hash) or die("could not move file");
		$img_hash = $hash;
		echo($hash);
	} elseif ($_FILES['screenshot']['error'] != UPLOAD_ERR_OK) {
		//  Upload was not successful.
		die($_FILES['screenshot']['error']);
	}


	$messageData = array();
	foreach($GLOBALS['config']['Support']['Order'] as $input_name){
		if($input_name !== "name" && $input_name !== "email" && $input_name !== "password" && $input_name !== "message")
		$messageData['values'][$input_name] = $_POST[$input_name];
	}
	$messageData['messages'] = array(
		array("id"=>1,"from"=>array(
			"id"=>$_SESSION['id'], "name"=>$inp_name, "email"=>$inp_email
		),"body"=>$message,"image"=>$img_hash)
	);
	
	
	

	$messageJson = json_encode($messageData);

	try{
		$response = $cda->insert("threads",array('id','owner','status','subject','date','lastreply','data'),array($threadid,$_SESSION['id'],'Pending',substr($message, 0, 50),date("Y-m-d H:i:s"),$_SESSION['id'],$messageJson));
	} catch (Exception $e){
		die($e);
	}


	$url = 'http://' . $_SERVER['SERVER_NAME'] . str_replace('new_thread','thread',$_SERVER['PHP_SELF']) . '?id=' . $threadid;

	// Emails password reset link.
	mailer_threadCreated($_SESSION['name'], $_SESSION['email'], $url);
	
	header("Location: thread.php?id=" . $threadid . "&notice=new");
}
writeDOC:


$inputs = array();

foreach($GLOBALS['config']['Support']['Order'] as $input_name){
	if($input_name != "name" && $input_name != "email" && $input_name != "password" && $input_name != "message")
	$inputs[$input_name] = $designer['config'][$input_name];
}

require('lucy-themes/' . $GLOBALS['config']['Theme'] . '/new_thread.php');