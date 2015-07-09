<?php

	// Thread Created Email
	// $name : User Name
	// $email : User Email
	// $url : Thread URL
	function mailer_threadCreated($name, $email, $url){
		$headers = 'From: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'Reply-To: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'Thread Created',"<p>Hey there, " . $name . "!</p><p>This email is just to let you know that your thread has been created. You can access your thread using <a href=\"" . $url . "\">this link</a></p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}

	// Thread Update Email
	// $name : User Name
	// $email : User Email
	// $url : Thread URL
	function mailer_threadUpdate($name, $email, $url){
		$headers = 'From: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'Reply-To: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'New Reply',"<p>Hey there, " . $name . "!</p><p>Somebody replied to one of your threads.  You should go check it out. <a href=\"" . $url . "\">View Reply</a></p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}

	// Password Reset PIN Email
	// $email : User Email
	// $pin : Reset PIN
	function mailer_passwordReset($email, $pin){
		$headers = 'From: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'Reply-To: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'Password Reset',"<p>Hey there</p><p>You requested to reset your password.  To do this, you must enter this 6-digit PIN to verify your identity. <strong>This PIN will expire in one day, so don't delay.</strong></p><h1>" . $pin . "</h1><p><strong>If you did not request a password reset:</strong> you can safely ignore this email and let the PIN time out.</p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}

	// Password Rest Notification Email
	// $email : User Email
	function mailer_passwordResetNotice($email){
		$headers = 'From: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'Reply-To: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'Password Reset',"<p>Hey there</p><p>Your password was changed, this is just a heads up</p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}

	// Email verification
	// $name : User Name
	// $email : User Email
	// $url : Thread URL
	function mailer_emailVerify($name, $email, $url){
		$headers = 'From: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'Reply-To: ' . $GLOBALS['config']['Email']['Address'] ."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'Email Verification',"<p>Hey there, " . $name . "!</p><p>We wanna make sure that this email is working, so if anything important is sent to it we know you'll see it.  <a href=\"$url\">Click this link</a> and your email address will be verified.  This link will only last for one day, however!  If you didn't request an email verification, just ignore this email.</p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}
?>