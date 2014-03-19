<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

function get_welcomeMessage($name, $email) {
	return('<html><body><h1>Welcome to Lucy, ' . $name . '!</h1>  Your account has been created and you\'re all set.  This email is just letting you know that.</body></html>');
}

function get_threadUpdate($name, $email, $id) {
	return('<html><body><h1>Hey, ' . $name . ', there\'s been activity on your thread!</h1>Better go check it out <a href="' . $url . '">here</a></body>');
}

function get_passwordReset($name, $email, $url) {
	return('<html><body><h1>Hey, ' . $name . ', need a new password?</h1>No worries!  Just click <a href="' . $url . '">here</a> to have a new password emailed to you.</body>');
}

function get_generalMessage($name, $email, $body) {
	return('<html><body><h1>Hey, ' . $name . ', this is important!</h1>' . $body . '</body>');
}