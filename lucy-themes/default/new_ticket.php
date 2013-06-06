<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Welcome'); ?>
<link href="<?php echo($GLOBALS['config']['Domain']); ?>lucy-themes/default/assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script src="<?php echo($GLOBALS['config']['Domain']); ?>lucy-themes/default/assets/js/bootstrap.fileupload.js"></script>
<?php getNav(1); ?>
<h1>Submit a ticket</h1>
<form method="POST" name="fm_ticket" onsubmit="return validateForm()" enctype="multipart/form-data" class="form-horizontal">
	<?php if(!$usr_IsSignedIn){ ?>
		<div class="control-group">
			<label class="control-label">What is your Name?</label>
			<div class="controls">
				<input type="text" name="name" maxlength="45" size="35" value="<?php echo($_GET['n']); ?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">What is your Email?</label>
			<div class="controls">
				<input type="email" name="email" maxlength="45" size="35" value="<?php echo($_GET['e']); ?>"/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Chose a password:</label>
			<div class="controls">
				<input type="password" name="password" maxlength="45" value="<?php echo($_GET['p']); ?>"/>
			</div>
		</div>
		<hr/>
	<?php } ?>
	<div class="control-group">
		<label class="control-label">What is the application you are using?</label>
		<div class="controls">
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
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">What is the version of that application?</label>
		<div class="controls">
			<input type="text" name="version" size="10" maxlength="7"/>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">What is the operating system you are using?</label>
		<div class="controls">
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
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">What is the problem?</label>
		<div class="controls">
			<textarea name="message" rows="10" cols="75" placeholder="Include things like: What actions you took to cause the problem, what you expected to happen, what actually happened." maxlength="16777216"></textarea>
		</div>
	</div>
	<?php if($GLOBALS['config']['Imgur']['Enable']) { ?>
	<div class="control-group">
		<label class="control-label">Include a screenshot? (<em>Optional</em>)</label>
		<div class="controls">
			<div class="fileupload fileupload-new" data-provides="fileupload">
				<span class="btn btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" name="screenshot"/></span>
				<span class="fileupload-preview"></span>
				<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
			</div>
		</div>
	</div>
	<?php } if($GLOBALS['config']['ReCaptcha']['Enable'] && $GLOBALS['config']['ReCaptcha']['Ticket']){
		if($cap_error){ ?>
		<div class="notice" id="yellow">
			<strong>Incorrect Captcha</strong> Try Again.
		</div>
		<?php }
		echo recaptcha_get_html($GLOBALS['config']['ReCaptcha']['Public']);
	} ?>
	<input type="submit" name="submit" value="Create Ticket" class="btn btn-primary" />
</form>
<?php getFooter(); ?>