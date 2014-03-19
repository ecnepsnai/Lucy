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

	$url = dirname(__FILE__) . '/../../lucy-config/config.json';

	$json = file_get_contents($url);
	$config = json_decode($json, true);

	$order = explode(",", $_POST['order']);

	$config['config']['Support']['Order'] = $order;

	$json = '{"config":' . json_encode($config['config']) . '}';

	file_put_contents('../../lucy-config/config.json', $json);
	$notice = True;

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