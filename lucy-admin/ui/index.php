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
		$response = $cda->select(array("id"),"ticketlist",null);
		if(!$response['data']){
			$ticketcount = 'no';
		} else {
			$ticketcount = count($response['data']);
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
<div class="hero-unit">
	<h1>Hello, <?php echo($usr_Name); ?>.</h1>
	<p>Currently there are: <strong><?php echo($ticketcount); ?></strong> Tickets, <strong><?php echo($replycount); ?></strong> Tickets awaiting response, and <strong><?php echo($usercount); ?></strong> Users.</p>
</div>
<?php getFooter(); ?>