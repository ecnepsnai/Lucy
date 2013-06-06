<?php
	// The path to the configuration file.
	$url = dirname(__FILE__) . '\..\lucy-config\config.json';

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
	$GLOBALS['readonly']['version'] = 'Beta 2';