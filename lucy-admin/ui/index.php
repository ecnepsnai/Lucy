<?php
	require("../session.php");
	require("default.php");
	include_once("../cda.php");
	
	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	// Administrator or Agent access only.
	if($usr_Type != "Admin" && $usr_Type != "Agent"){
		lucy_die(0);
	}



	try{
		$response = $cda->select(array("id"),"threads",null);
		if(!$response['data']){
			$threadcount = 'no';
		} else {
			$threadcount = count($response['data']);
		}
	} catch (Exception $e) {
		die($e);
	}

	try{
		$response = $cda->select(array("id"),"userlist",null);
		if(!$response['data']){
			$usercount = 'no';
		} else {
			$usercount = count($response['data']);
		}
	} catch (Exception $e) {
		die($e);
	}

	getHeader("Dashboard");
	getNav(0);
?>
<div class="jumbotron">
	<h1>Hello, <?php echo($usr_Name); ?>.</h1>
	<p>Currently there are: <strong><?php echo($threadcount); ?></strong> threads, <strong><?php echo($replycount); ?></strong> threads awaiting response, and <strong><?php echo($usercount); ?></strong> Users.</p>
	<p><a class="btn btn-primary btn-lg" role="button" href="mythreads.php">Get Down to Work</a></p>
</div>
<?php getFooter(); ?>