<?php
	header('Content-Type: application/json');
	require("../session.php");
	require("../cda.php");
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

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	switch ($_POST['f']) {
		case 'all':
			try {
				$response = $cda->select(array("id","subject","date","assignedto","status"),"threads",null);
			} catch (Exception $e) {
				die($e);
			}
			$threads = $response['data'];

			// Correcting issue if there is only one item in the database.
			if(isset($threads['id'])){
				$threads = array($threads);
			}

			for ($i=0; $i < count($threads); $i++) { 
				if($threads[$i]['status'] == "Closed"){
					unset($threads[$i]);
				}
			}

			echo('{"response": {"code":200,"data":' . json_encode($threads) . '}}');
		break;

		case 'mine':
			try {
				$response = $cda->select(array("id","subject","date","status"),"threads",array('assignedto'=>$usr_ID));
			} catch (Exception $e) {
				die($e);
			}
			$threads = $response['data'];

			// Correcting issue if there is only one item in the database.
			if(isset($threads['id'])){
				$threads = array($threads);
			}

			for ($i=0; $i < count($threads); $i++) { 
				if($threads[$i]['status'] == "Closed"){
					unset($threads[$i]);
				}
			}

			echo('{"response": {"code":200,"data":' . json_encode($threads) . '}}');
		break;

		case 'closed':
			try {
				$response = $cda->select(array("id","subject","date","assignedto"),"threads",array('status'=>'Closed'));
			} catch (Exception $e) {
				die($e);
			}
			$threads = $response['data'];

			// Correcting issue if there is only one item in the database.
			if(isset($threads['id'])){
				$threads = array($threads);
			}

			echo('{"response": {"code":200,"data":' . json_encode($threads) . '}}');
		break;
		
		default:
			# code...
		break;
	}