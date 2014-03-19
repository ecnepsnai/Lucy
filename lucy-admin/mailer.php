<?php

	function mailer_threadCreated($name, $email, $url){
		$headers = 'From: lucy@ecnepsnai.com'."\r\n".
		'Reply-To: lucy@ecnepsnai.com'."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'Thread Created',"<p>Hey there, " . $name . "!</p><p>This email is just to let you know that your thread has been created. You can access your thread using <a href=\"" . $url . "\">this link</a></p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}
	function mailer_threadUpdate($name, $email, $id){
		require("../cda.php");
		// Creating the CDA class.
		$cda = new cda;
		// Initializing the CDA class.
		$cda->init($GLOBALS['config']['Database']['Type']);
		$response = $cda->select(array('Name','Email'),$id,array('UpdateID'=>1));
		$user = $response['data'];

		$headers = 'From: lucy@ecnepsnai.com'."\r\n".
		'Reply-To: lucy@ecnepsnai.com'."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($user['Email'],'New Reply on Thread',"<p>Hey there, " . $user['Name'] . "!</p><p>There is a new reply on your thread on Lucy. <a href=\"" . $GLOBALS['config']['siteURL'] . "thread.php?id=" . $thread . "\">Read it here</a></p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}
	function mailer_passwordReset($name, $email, $url){
		$headers = 'From: lucy@ecnepsnai.com'."\r\n".
		'Reply-To: lucy@ecnepsnai.com'."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'Lucy Password Reset',"<p>Hey there, " . $name . "!</p><p>A little bird told me you're having trouble logging into Lucy.  No worries, it happens to the best of us.  <a href=\"$url\">Click this link</a> and you'll be back in action in a jiffy! (Don't wait too long though, that link will expire in 12hrs!)  If you didn't request a password reset, just ignore this email.</p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}
	function mailer_generatedPassword($name, $email, $password){
		$headers = 'From: lucy@ecnepsnai.com'."\r\n".
		'Reply-To: lucy@ecnepsnai.com'."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'Lucy Password',"<p>Hey there!</p>You requested to have your password reset, so we went ahead and did that for you.  Your new password is:<p><pre>" . $password . "</pre><p>You should change it as soon as you log back in. Your old password will no longer work.</p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}
	function mailer_emailVerify($name, $email, $url){
		$headers = 'From: lucy@ecnepsnai.com'."\r\n".
		'Reply-To: lucy@ecnepsnai.com'."\r\n".
		'MIME-Version: 1.0'."\r\n".
		'Content-type: text/html; charset=iso-8859-1'."\r\n".
		'X-Mailer: PHP/'.phpversion();
		$result = mail($email,'Lucy Email Verification',"<p>Hey there, " . $name . "!</p><p>We wanna make sure that this email is working, so if anything important is sent to it we know you'll see it.  <a href=\"$url\">Click this link</a> and your email address will be verified.  This link will only last for one day, however!  If you didn't request an email verification, just ignore this email.</p>" . $GLOBALS['config']['Email']['Footer'], $headers);
	}
	function mailer_generalMessage($name, $email, $subject, $body){
		
	}
?>