<?php
	// The path to the configuration file.
	$url = dirname(__FILE__) . '/../lucy-config/config.json';

	// Verifying that the file exists.  If not it will redirect the user to the configuration page.
	// NOTE: SEE THE DOCUMENTATION FOR PERMISSIONS RELATING TO THIS CONFIGUATION FILE.
	if(!file_exists($url)){
		header("Location: lucy-setup/");
	}

	// Getting the contents and serializing them.
	$json = file_get_contents($url);
	$data = json_decode($json, true);

	// If the JSON document is not valid the decoder will not return anything.
	if(empty($data)){
		die("The configuration file is not valid and could not be parsed.");
	}
	$GLOBALS['config'] = $data['config'];
	$GLOBALS['readonly']['version'] = 'Beta 4';

	// The path to the designer file.
	$url = dirname(__FILE__) . '/../lucy-config/designer.json';

	// Getting the contents and serializing them.
	$json = file_get_contents($url);
	$data = json_decode($json, true);

	// If the JSON document is not valid the decoder will not return anything.
	if(empty($data)){
		die("The designer file is not valid and could not be parsed.");
	}
	$GLOBALS['designer'] = $data;

	// Returns a sorted list of inputs from the designer file
	function getInputs(){
		$inputs = array();
		foreach($GLOBALS['config']['Support']['Order'] as $input_name){
			switch ($input_name) {
				case 'name':
				break;
				case 'email':
				break;
				case 'password':
				break;
				case 'message':
				break;
				case 'image':
				break;
				default:
					$inputs[$input_name] = $GLOBALS['designer']['config'][$input_name];
				break;
			}
		}
		return $inputs;
	}