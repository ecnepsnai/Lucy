<?php
	// The path to the configuration file.
	$url = $_SERVER['DOCUMENT_ROOT'] . '/lucy/lucy-config/config.json';

	// Verifying that the file exists.  If not it will redirect the user to the configuration page.
	// NOTE: SEE THE DOCUMENTATION FOR PERMISSIONS RELATING TO THIS CONFIGUATION FILE.
	if(!file_exists($url)){
		die("<meta http-equiv=\"REFRESH\" content=\"0;url=lucy-setup\\\">Redirecting...");
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


	// If the page is directly accessed and debug mode is enabled we output some debug information.
	if(strpos(strtolower($_SERVER['SCRIPT_NAME']),strtolower(basename(__FILE__)))){
		die(var_dump($GLOBALS['config']));
	}