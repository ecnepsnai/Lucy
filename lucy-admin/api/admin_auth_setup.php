<?php
	require("../session.php");
	$error = array();

	// Requires user to be signed in
	if($usr_IsSignedIn === false){
		$error = array("code"=>401,"message"=>"Authentication required");
		?>{
			"response": {
				"code":<?php echo($error['code']); ?>,
				"message":"<?php echo($error['message']); ?>"
			}
		}<?php
		die();
	}

	// Only the owner of the ticked or Admins may alter it.
	if($usr_Type != "Admin") {
		$error = array("code"=>403,"message"=>"Authentication failed");
		?>{
			"response": {
				"code":<?php echo($error['code']); ?>,
				"message":"<?php echo($error['message']); ?>"
			}
		}<?php
		die();
	}

	if(!isset($_GET['s'])){
		$error = array("code"=>400,"message"=>"Missing stage number");
		?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
	}



	/*
	Stages for Authentication Setup
	Stage 1: Verify Password
	Stage 2: Generate and Update Secret
	Stage 3: Verify Token & Generate Backup
	Stage 4: Remove Secret (No Password)
	Stage 5: Remove Secret (Password)
	*/

	switch ($_GET['s']) {
		case '1':
			if(!isset($_POST['password'])){
				$error = array("code"=>400,"message"=>"Missing password");
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


			// Ask for user salt first, then verify, THEN get rest of data
			try {
				$response = $cda->select(array("salt"),"userlist",array("id"=>$usr_ID));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			$pw = $response['data'];
			$pw_hash = md5($pw['salt'] . md5(trim($_POST['password'])));

			// Preparing the second sql request.
			try {
				$response = $cda->select(array("id"),"userlist",array("id"=>$usr_ID,"password"=>$pw_hash));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			$user = $response['data'];
			if(empty($user['id'])){
				$error = array("code"=>403,"message"=>"Bad password");
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			} else {
				?>{
					"response": {
						"code":200,
						"message":"OK"
					}
				}<?php
			}
		break;
		case '2':
			// Requiring the CDA library.
			require("../cda.php");

			// Creating the CDA class.
			$cda = new cda;
			// Initializing the CDA class.
			$cda->init($GLOBALS['config']['Database']['Type']);
			require("../auth.php");
			$tf = new tfa;
			$secret = $tf->createSecret();
			try{
				$response = $cda->update("userlist", array("tf_secret"=>$secret),array("id"=>$usr_ID));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			?>{
				"response": {
					"code":200,
					"message":"<?php echo($secret); ?>"
				}
			}<?php
		break;
		case '3':
			if(!isset($_POST['code'])){
				$error = array("code"=>400,"message"=>"Missing code");
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

			require("../auth.php");
			$tf = new tfa;


			try {
				$response = $cda->select(array("tf_secret","salt"),"userlist",array("id"=>$usr_ID));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}

			if($tf->verifyCode($response['data']['tf_secret'],$_POST['code'],2) === false){
				?>{
					"response": {
						"code":403,
						"message":"Bad Token"
					}
				}<?php
				die();
			}

			$backup = mt_rand(100000,999999);
			$hashed_backup = md5($response['data']['salt'] . md5($backup));
			try {
				$response = $cda->update("userlist", array("tf_backup"=>$hashed_backup),array("id"=>$usr_ID));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			?>{
				"response": {
					"code":200,
					"message":"<?php echo($backup); ?>"
				}
			}<?php
		break;
		case '4':
			// Requiring the CDA library.
			require("../cda.php");

			// Creating the CDA class.
			$cda = new cda;
			// Initializing the CDA class.
			$cda->init($GLOBALS['config']['Database']['Type']);
			try{
				$response = $cda->update("userlist", array("tf_secret"=>""),array("id"=>$usr_ID));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			?>{
				"response": {
					"code":200,
					"message":""
				}
			}<?php
		break;
		case '5':
			if(!isset($_POST['password'])){
				$error = array("code"=>400,"message"=>"Missing password");
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


			// Ask for user salt first, then verify, THEN get rest of data
			try {
				$response = $cda->select(array("salt"),"userlist",array("id"=>$usr_ID));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			$pw = $response['data'];
			$pw_hash = md5($pw['salt'] . md5(trim($_POST['password'])));

			// Preparing the second sql request.
			try {
				$response = $cda->select(array("id"),"userlist",array("id"=>$usr_ID,"password"=>$pw_hash));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			$user = $response['data'];
			if(empty($user['id'])){
				$error = array("code"=>403,"message"=>"Bad password");
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			try{
				$response = $cda->update("userlist", array("tf_secret"=>""),array("id"=>$usr_ID));
			} catch (Exception $e) {
				?>{
					"response": {
						"code":<?php echo($error['code']); ?>,
						"message":"<?php echo($error['message']); ?>"
					}
				}<?php
				die();
			}
			?>{
				"response": {
					"code":200,
					"message":""
				}
			}<?php
		break;
	}