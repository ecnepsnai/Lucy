<?php
	require("../session.php");
	$error = array();

	// Requires user to be signed in
	if($usr_IsSignedIn === false){
		$error = array("code"=>401,"message"=>"Authentication required");
		goto writeDoc;
	}

	// Only the owner of the ticked or Admins may alter it.
	if($usr_Type != "Admin") {
		$error = array("code"=>403,"message"=>"Authentication failed");
		goto writeDoc;
	}


	require("../cda.php");
	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);
	


	try{
		$response = $cda->select(array("name","id"),"userlist",array("type"=>"Admin"));
	} catch (Exception $e) {
		$error = array("code"=>500,"message"=>$e);
		goto writeDoc;
	}
	$users = $response['data'];

	// Correcting issue if there is only one item in the database.
	if(isset($users['name'])){
		$users = array($users);
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
		"message":"",
		"data":{"users":
			<?php echo(json_encode($users)); ?>
		}
	}
}
<?php }