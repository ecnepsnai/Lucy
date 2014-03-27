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

	$obj_name = $_POST['name'];
	$obj_type = $_POST['type'];

	if(empty($obj_name) || empty($obj_type)){
		$error == array("code"=>400,"message"=>"Object Name or Type not included.");
		goto writeDoc;
	}

	$obj_required = $_POST['required'];

	$obj_array = array();

	switch ($obj_type) {
		case 'text':
			$obj_title = $_POST['title'];
			$obj_helptext = $_POST['helptext'];
			$obj_required = $_POST['required'];
			$obj_length_min = $_POST['length_min'];
			$obj_length_max = $_POST['length_max'];
			$obj_acpt_num = $_POST['acpt_num'];
			$obj_acpt_sym = $_POST['acpt_sym'];
			$obj_array = array(
				"type"=>"text",
				"title"=>$obj_title,
				"helptext"=>$obj_helptext,
				"required"=>$obj_required,
				"length_min"=>$obj_length_min,
				"length_max"=>$obj_length_max,
				"acpt_num"=>$obj_acpt_num,
				"acpt_sym"=>$obj_acpt_sym
			);
		break;
		case 'number':
			$obj_title = $_POST['title'];
			$obj_helptext = $_POST['helptext'];
			$obj_required = $_POST['required'];
			$obj_length_min = $_POST['length_min'];
			$obj_length_max = $_POST['length_max'];
			$obj_array = array(
				"type"=>"number",
				"title"=>$obj_title,
				"helptext"=>$obj_helptext,
				"required"=>$obj_required,
				"length_min"=>$obj_length_min,
				"length_max"=>$obj_length_max
			);
		break;
		case 'range':
			$obj_title = $_POST['title'];
			$obj_helptext = $_POST['helptext'];
			$obj_required = $_POST['required'];
			$obj_length_min = $_POST['length_min'];
			$obj_length_max = $_POST['length_max'];
			$obj_array = array(
				"type"=>"range",
				"title"=>$obj_title,
				"helptext"=>$obj_helptext,
				"required"=>$obj_required,
				"length_min"=>$obj_length_min,
				"length_max"=>$obj_length_max
			);
		break;
		case 'select':
			$obj_type = $_POST['type'];
			$obj_title = $_POST['title'];
			$obj_helptext = $_POST['helptext'];
			$obj_required = $_POST['required'];
			$obj_options = $_POST['options'];
			$obj_array = array(
				"type"=>$obj_type,
				"title"=>$obj_title,
				"helptext"=>$obj_helptext,
				"required"=>$obj_required,
				"options"=>$obj_options
			);
		break;
		case 'textarea':
			$obj_title = $_POST['title'];
			$obj_helptext = $_POST['helptext'];
			$obj_required = $_POST['required'];
			$obj_array = array(
				"type"=>"textarea",
				"title"=>$obj_title,
				"helptext"=>$obj_helptext,
				"required"=>$obj_required
			);
		break;
		case 'checkbox':
			$obj_type = $_POST['type'];
			$obj_title = $_POST['title'];
			$obj_helptext = $_POST['helptext'];
			$obj_required = $_POST['required'];
			$obj_options = $_POST['options'];
			$obj_array = array(
				"type"=>$obj_type,
				"title"=>$obj_title,
				"helptext"=>$obj_helptext,
				"required"=>$obj_required,
				"options"=>$obj_options
			);
		break;
		case 'radio':
			$obj_type = $_POST['type'];
			$obj_title = $_POST['title'];
			$obj_helptext = $_POST['helptext'];
			$obj_required = $_POST['required'];
			$obj_options = $_POST['options'];
			$obj_array = array(
				"type"=>$obj_type,
				"title"=>$obj_title,
				"helptext"=>$obj_helptext,
				"required"=>$obj_required,
				"options"=>$obj_options
			);
		break;
		
		default:
			# code...
			break;
	}

	$designer['config'][$obj_name] = $obj_array;

	$json = '{"config":' . json_encode($designer['config']) . ',"static":' . json_encode($designer['static']) . '}';

	file_put_contents('../../lucy-config/designer.json', $json);

	$curl = dirname(__FILE__) . '/../../lucy-config/config.json';

	$cjson = file_get_contents($curl);
	$cconfig = json_decode($cjson, true);
	array_push($cconfig['config']['Support']['Order'], $obj_name);
	$cjson = '{"config":' . json_encode($cconfig['config']) . '}';
	file_put_contents('../../lucy-config/config.json', $cjson);

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