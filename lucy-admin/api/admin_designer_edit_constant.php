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

	$url = dirname(__FILE__) . '/../../lucy-config/designer.json';

	$json = file_get_contents($url);
	$designer = json_decode($json, true);

	$const_name = $_POST['name'];
	$const_title = $_POST['title'];
	$const_helptext = $_POST['helptext'];

	$obj_array = array(
		"title"=>$const_title,
		"helptext"=>$const_helptext
	);

	$designer['static'][$const_name] = $obj_array;

	$json = '{"config":' . json_encode($designer['config']) . ',"static":' . json_encode($designer['static']) . '}';

	file_put_contents('../../lucy-config/designer.json', $json);
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