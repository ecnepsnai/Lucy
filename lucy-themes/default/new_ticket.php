<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Welcome'); ?>
<h1>Submit a ticket</h1>
<script language="javascript" type="text/javascript">
function popitup(url) {
	newwindow=window.open(url,'name','height=650,width=800');
	if (window.focus) {newwindow.focus()}
	return false;
}
</script>
<form method="POST" name="fm_ticket" onsubmit="return validateForm()" enctype="multipart/form-data">
	<?php if(!$usr_IsSignedIn){ ?>
	<p>
		What is your name?<br/>
		<input type="text" name="name" maxlength="45" size="35"/>
	</p>
	<p>
		What is your email?<br/>
		<input type="email" name="email" maxlength="45" size="35"/>
	</p>
	<?php } ?>
	<p>
		What is the application you are using?<br/>
		<select name="app">
			<option value="">Select One..</option>
			<?php
				foreach($GLOBALS['config']['Apps'] as $app){
					echo('<option value="' . $app . '">' . $app . '</option>');
				}
			?>
		</select>
	 </p>
	<p>
		What is the version of that application?<br/>
		<input type="text" name="version" size="10" maxlength="7"/>
	</p>
	<p>
		What is the operating system you are using? - <a href="javascript:popitup('//assets.ianspence.com/lucy/os.html')">Help</a><br/>
		<select name="os" onchange="validateOS(value)">
			<option value="">Select One...</option>
			<option disabled="disabled">Microsoft Windows</option>
			<option value="WinXP">Windows XP</option>
			<option value="WinVx32">Windows Vista (32bit)</option>
			<option value="WinVx64">Windows Vista (64bit)</option>
			<option value="Win7x32">Windows 7 (32bit)</option>
			<option value="Win7x64">Windows 7 (64bit)</option>
			<option value="Win8x32">Windows 8 (RT)</option>
			<option value="Win8x64">Windows 8 (64bit / Pro)</option>
			<option disabled="disabled">Mac OS X</option>
			<option value="OSX106">Mac OS X Leopard</option>
			<option value="OSX107">Mac OS X Snow Leopard</option>
			<option value="OSX108">Mac OS X Lion</option>
			<option value="OSX109">Mac OS X Mountain Lion</option>
			<option disabled="disabled">Ubuntu</option>
			<option value="UBU10.10">Ubuntu: 10.10 - Maverick Meerkat</option>
			<option value="UBU11.04">Ubuntu: 11.04 - Natty Narwhal</option>
			<option value="UBU11.10">Ubuntu: 11.10 - Oneiric Ocelot</option>
			<option value="UBU12.04">Ubuntu: 12.04LTS - Precise Pangolin</option>
			<option value="UBU12.10">Ubuntu: 12.10 - Quantal Quetzal</option>
			<option disabled="disabled">Apple iOS</option>
			<option value="iOS421">iOS 4.2.1</option>
			<option value="iOS511">iOS 5.1.1</option>
			<option value="iOS601">iOS 6.0.1</option>
			<option value="iOS61B3">iOS 6.2</option>
		</select>
	</p>
	<div id="osresult" class="message_client" style="display: none;"></div>
	<p>
		What is the problem?<br/>
		<textarea name="message" rows="10" cols="75" placeholder="Include things like: What actions you took to cause the problem, what you expected to happen, what actually happened." maxlength="16777216"></textarea>
	</p>
	<?php if($GLOBALS['config']['Imgur']['Enable']) { ?>
	<p>
		Include a screenshot? (<em>Optional</em> - <a href="javascript:popitup('//assets.ianspence.com/lucy/screenshots.html')">Help</a>)<br/>
		<input type="file" name="screenshot" />
	</p>
	<?php } if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Ticket']){
		if($cap_error){ ?>
		<div class="notice" id="yellow">
			<strong>Incorrect Captcha</strong> Try Again.
		</div>
		<?php }
		echo("<p>");
		echo recaptcha_get_html($GLOBALS['config']['ReCaptcha']['Public']);
		echo("</p>");
	} ?>
	<p><input type="submit" name="submit" value="Create Ticket" class="btn" id="blue"/></p>
</form>
<?php getFooter(); ?>