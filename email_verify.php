<?php
require("lucy-admin/session.php");

// Obviously if the user is not signed in, we don't let them verify their email.
if(!$usr_IsSignedIn){
	header("Location: index.php");
}

// Requiring the CDA library.
require("lucy-admin/cda.php");

// Creating the CDA class.
$cda = new cda;
// Initializing the CDA class.
$cda->init($GLOBALS['config']['Database']['Type']);

if(isset($_GET['t'])){
	$test_token = $_GET['t'];
	$response = null;

	try{
		$response = $cda->select(array('email','salt'),'userlist',array('id'=>$usr_ID));
	} catch (Exception $e){
		lucy_error('Database Error',$e, true);
	}

	$true_token = md5($response['data']['email'] . $response['data']['salt']);
	if($test_token == $true_token){
		try{
			$cda->update("userlist",array("verified"=>1),array('id'=>$usr_ID));
		} catch (Exception $e){
			lucy_error('Database Error',$e, true);
		}
		header('location: index.php?verify=confirmed');
	}
} else {
	$token = "";
	$response = null;

	try{
		$response = $cda->select(array('email','salt'),'userlist',array('id'=>$usr_ID));
	} catch (Exception $e){
		lucy_error('Database Error',$e, true);
	}

	$token = md5($response['data']['email'] . $response['data']['salt']);
	$url = $GLOBALS['config']['Paths']['Remote'] . 'email_verify.php?t=' . $token;
	mailer_emailVerify($usr_Name, $usr_Email, $url);
	header('location: index.php?verify=sent');
}