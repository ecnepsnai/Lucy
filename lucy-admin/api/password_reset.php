<?php
	require("../session.php");
	$error = array();

	// Requires user to be signed in
	if($usr_IsSignedIn === true){
		$error = array("code"=>401,"message"=>"Authentication failure");
		?>{
			"response": {
				"code":<?php echo($error['code']); ?>,
				"message":"<?php echo($error['message']); ?>"
			}
		}<?php
		die();
	}

	if(!isset($_POST['email'])){
		$error = array("code"=>400,"message"=>"Missing email");
		?>{
			"response": {
				"code":<?php echo($error['code']); ?>,
				"message":"<?php echo($error['message']); ?>"
			}
		}<?php
		die();
	}

	// Requiring the CDA library.
	require("../cda.php");

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	$reset_token = mt_rand(100000,999999);

	try{
		$response = $cda->select(array('id'),'userlist',array('email'=>$_POST['email']));
	} catch (Exception $e) {
		?>{
			"response": {
				"code":500,
				"message":"<?php echo($e); ?>"
			}
		}<?php
		die();
	}

	if(!isset($response['data']['id'])){
		$error = array("code"=>400,"message"=>"Bad email");
		?>{
			"response": {
				"code":<?php echo($error['code']); ?>,
				"message":"<?php echo($error['message']); ?>"
			}
		}<?php
		die();
	}

	try{
		$response = $cda->select(array('id','expire_date'),'resetlist',array('email'=>$_POST['email']));
	} catch (Exception $e) {
		?>{
			"response": {
				"code":500,
				"message":"<?php echo($e); ?>"
			}
		}<?php
		die();
	}

	if(isset($response['data']['id'])){
		$date = new DateTime($response['data']['expire_date']);
		$now = new DateTime();
		if($now < $date){
			$error = array("code"=>400,"message"=>"Reset already pending");
			?>{
				"response": {
					"code":<?php echo($error['code']); ?>,
					"message":"<?php echo($error['message']); ?>"
				}
			}<?php
			die();
		} else {
			try{
				$response = $cda->delete('resetlist',array('email'=>$_POST['email']));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":500,
						"message":"<?php echo($e); ?>"
					}
				}<?php
				die();
			}
		}	
	}

	$create_date = new DateTime();
	$expire_date = new DateTime();
	$expire_date->modify('+1 day');

	try{
		$response = $cda->insert('resetlist',array('email','create_date','expire_date','ip','pin'),array($_POST['email'],$create_date->format('Y-m-d\ H:i:s'),$expire_date->format('Y-m-d\ H:i:s'),$_SERVER['REMOTE_ADDR'],$reset_token));
	} catch (Exception $e) {
		?>{
			"response": {
				"code":500,
				"message":"<?php echo($e); ?>"
			}
		}<?php
		die();
	}

	mailer_passwordReset($_POST['email'], $reset_token);

	?>{
		"response": {
			"code":200,
			"message":"<?php echo($reset_token); ?>"
		}
	}<?php
	die();