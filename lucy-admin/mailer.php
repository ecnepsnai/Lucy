<?php
	include_once('lucy-themes/' . $GLOBALS['config']['Theme'] . '/mailer.php');
	function mailer_welcomeMessage($name, $email){
		$subject = 'Welcome to Lucy!';
		$message = get_welcomeMessage($name, $email);
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: "' . $GLOBALS['config']['Email']['Name'] . '" <' . $GLOBALS['config']['Email']['Address'] . '>';
		echo($headers);
		mail($email, $subject, $message, $headers);
	}
	function mailer_ticketUpdate($name, $email, $id){
		$subject = 'New activity on your Ticket';
		$message = get_ticketUpdate($name, $email, $id);
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: "' . $GLOBALS['config']['Email']['Name'] . '" <' . $GLOBALS['config']['Email']['Address'] . '>';
		echo($headers);
		mail($email, $subject, $message, $headers);
	}
	function mailer_passwordReset($name, $email, $url){
		$subject = 'Password Reset';
		$message = get_passwordReset($name, $email, $url);
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: "' . $GLOBALS['config']['Email']['Name'] . '" <' . $GLOBALS['config']['Email']['Address'] . '>';
		echo($headers);
		mail($email, $subject, $message, $headers);
	}
	function mailer_generalMessage($name, $email, $subject, $body){
		$message = get_generalMessage($name, $email, $body);
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: "' . $GLOBALS['config']['Email']['Name'] . '" <' . $GLOBALS['config']['Email']['Address'] . '>';
		echo($headers);
		mail($email, $subject, $message, $headers);
	}
?>