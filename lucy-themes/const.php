<?php
	// Theme HTML constructor

	require("../lucy-admin/defines.php");

	// Verifying that the theme information file for the current theme exists.
	$url = $GLOBALS['config']['Theme'] . '/theme_info.json';
	if(!file_exists($url)){
		die("The theme information file could not be accessed.");
	}