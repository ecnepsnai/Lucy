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
			What is your Name?<br/>
			<input type="text" name="name" maxlength="45" size="35" value="<?php echo($_GET['n']); ?>"/>
		</p>
		<p>
			What is your Email?<br/>
			<input type="email" name="email" maxlength="45" size="35" value="<?php echo($_GET['e']); ?>"/>
		</p>
		<p>
			Chose a password:<br/>
			<input type="password" name="password" maxlength="45" value="<?php echo($_GET['p']); ?>"/>
		</p>
		<hr/>
	<?php } ?>
	<p>
		What is the application you are using?<br/>
		<select name="app">
			<option value="">Select One..</option>
			<?php
				foreach($GLOBALS['config']['Support']['Apps'] as $app){
					if(strncmp($app, "#", strlen("#"))){
						echo('<option value="' . $app . '">' . $app . '</option>');
					} else {
						echo('<option disabled>' . str_replace("#", "", $app) . '</option>');
					}
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
			<?php
				foreach($GLOBALS['config']['Support']['OS'] as $os){
					if(strncmp($os, "#", strlen("#"))){
						echo('<option value="' . $os . '">' . $os . '</option>');
					} else {
						echo('<option disabled>' . str_replace("#", "", $os) . '</option>');
					}
				}
			?>
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