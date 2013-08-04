<?php
	require("../session.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	// User chose to save the settings.
	if(isset($_POST['submit'])){
		// Getting the general settings:
		$GLOBALS['config']['SessionExpire'] = $_POST['expire'];
		$GLOBALS['config']['Debug'] = $_POST['debug'];


		// Getting the Email settings:
		$GLOBALS['config']['Email']['Name'] = $_POST['em_name'];
		$GLOBALS['config']['Email']['Address'] = $_POST['em_address'];
		$GLOBALS['config']['Email']['Advance'] = $_POST['em_advance'];
		$GLOBALS['config']['Email']['Host'] = $_POST['em_host'];
		$GLOBALS['config']['Email']['Auth'] = $_POST['em_auth'];
		$GLOBALS['config']['Email']['Port'] = $_POST['em_port'];
		$GLOBALS['config']['Email']['Username'] = $_POST['em_username'];
		$GLOBALS['config']['Email']['Password'] = $_POST['em_password'];
		$GLOBALS['config']['Email']['Type'] = $_POST['em_type'];


		// Getting the Support settings:
		$GLOBALS['config']['Support']['Apps'] = explode(",", $_POST['sup_app']);
		$GLOBALS['config']['Support']['OS'] = explode(",", $_POST['sup_os']);
		$GLOBALS['config']['Support']['ID'] = $_POST['sup_id'];

		// Getting the Database Settings:
		$GLOBALS['config']['Database']['Type'] = $_POST['db_type'];
		$GLOBALS['config']['Database']['Location'] = $_POST['db_location'];
		$GLOBALS['config']['Database']['Name'] = $_POST['db_name'];
		$GLOBALS['config']['Database']['Username'] = $_POST['db_username'];
		$GLOBALS['config']['Database']['Password'] = $_POST['db_password'];

		// Getting the reCAPTCHA Settings:
		$GLOBALS['config']['ReCaptcha']['Enable'] = $_POST['cap_enable'];
		$GLOBALS['config']['ReCaptcha']['Public'] = $_POST['cap_public'];
		$GLOBALS['config']['ReCaptcha']['Private'] = $_POST['cap_private'];
		$GLOBALS['config']['ReCaptcha']['Signup'] = $_POST['cap_signup'];
		$GLOBALS['config']['ReCaptcha']['Login'] = $_POST['cap_login'];
		$GLOBALS['config']['ReCaptcha']['Ticket'] = $_POST['cap_ticket'];

		// Gettings the Imgur Settings:
		$GLOBALS['config']['Imgur']['Enable'] = $_POST['img_enable'];
		$GLOBALS['config']['Imgur']['Key'] = $_POST['img_key'];

		// Getting the Strings Settings:
		$GLOBALS['config']['Strings']['Main'] = $_POST['txt_main'];
		$GLOBALS['config']['Strings']['Separator'] = $_POST['txt_sep'];
		$GLOBALS['config']['Strings']['Footer'] = $_POST['txt_ftr'];

		// Getting the Akismet Settings:
		$GLOBALS['config']['Akismet']['Enable'] = $_POST['aki_enable'];
		$GLOBALS['config']['Akismet']['Key'] = $_POST['aki_key'];

		$json = '{"config":' . json_encode($GLOBALS['config']) . '}';

		file_put_contents('../../lucy-config/config.json', $json);
		$notice = True;
	}

	if(isset($_POST['reset'])){
		file_put_contents('../../lucy-config/config.json', '');
		$notice = True;
	}

	getHeader("Settings");
	getNav(5);
?>
<div class="tabbable">
	<ul class="nav nav-pills">
		<li class="active"><a href="#general" data-toggle="tab">General</a></li>
		<li><a href="#email" data-toggle="tab">Email</a></li>
		<li><a href="#support" data-toggle="tab">Support</a></li>
		<li><a href="#database" data-toggle="tab">Database</a></li>
		<li><a href="#themes" data-toggle="tab">Themes</a></li>
		<li><a href="#reCAP" data-toggle="tab">reCAPTCHA</a></li>
		<li><a href="#imgur" data-toggle="tab">Imgur</a></li>
		<li><a href="#akismet" data-toggle="tab">Akismet</a></li>
	</ul>
	<form class="form-horizontal" method="post">
		<div class="tab-content">
			<div id="general" class="tab-pane active">
				<h2>General Settings:</h2>
				<div class="control-group">	
					<label class="control-label">Session Expiration Time:</label>
					<div class="controls">
						<input type="text" name="expire" size="30" value="<?php echo($GLOBALS['config']['SessionExpire']); ?>" title="The length of time a user has to be inactive until they will be logged out.  In milliseconds."/>
					</div>
				</div>
				<div class="control-group">	
					<label class="control-label">Enable Debug Mode:</label>
					<div class="controls">
						<input type="checkbox" name="debug" <?php if($GLOBALS['config']['Debug']){echo('checked="checked"');} ?> title="Debug mode enabled the output of debug information on certain libraries."/>
					</div>
				</div>
				<div class="control-group">	
					<label class="control-label">Main Title:</label>
					<div class="controls">
						<input type="text" name="txt_main" size="30" value="<?php echo($GLOBALS['config']['Strings']['Main']); ?>" title="This appears in the title and throughout the application."/>
					</div>
				</div>
				<div class="control-group">	
					<label class="control-label">Title Separator:</label>
					<div class="controls">
						<input type="text" name="txt_sep" size="5" maxlength="5" value="<?php echo($GLOBALS['config']['Strings']['Separator']); ?>" title="This appears in the title. Don't forget Trailing and Leading whitespace."/>
					</div>
				</div>
				<div class="control-group">	
					<label class="control-label">Footer Text:</label>
					<div class="controls">
						<input type="text" name="txt_ftr" size="30" value="<?php echo($GLOBALS['config']['Strings']['Footer']); ?>" title="The text that appears on the bottom left of the page."/>
					</div>
				</div>
			</div>
			<div id="email" class="tab-pane">
				<h2>Email Settings</h2>
				<div class="control-group">	
					<label class="control-label">Email Name:</label>
					<div class="controls">
						<input type="text" name="em_name" size="30" value="<?php echo($GLOBALS['config']['Email']['Name']); ?>" title="The email name used when Lucy sends out automatic emails to users."/>
					</div>
				</div>
				<div class="control-group">	
					<label class="control-label">Email Address:</label>
					<div class="controls">
						<input type="email" name="em_address" size="30" value="<?php echo($GLOBALS['config']['Email']['Address']); ?>" title="The email address used when Lucy sends out automatic emails to users."/>
					</div>
				</div>
				<div class="control-group">	
					<label class="control-label">Advance Mail Settings:</label>
					<div class="controls">
						<input type="checkbox" name="em_advance" <?php if($GLOBALS['config']['Email']['Advance']) { echo('checked="checked"'); } ?>/> (Check if your email requires SSL)
					</div>
				</div>
				<div id="email_advance" <?php if(!$GLOBALS['config']['Email']['Advance']) { echo('style="display:none"'); } ?>>
					<hr/>
					<div class="control-group">	
						<label class="control-label">Email Host:</label>
						<div class="controls">
							<input type="text" name="em_host" size="30" value="<?php echo($GLOBALS['config']['Email']['Host']); ?>" title="The SMPT host."/>
						</div>
					</div>
					<div class="control-group">	
						<label class="control-label">Require Authentication:</label>
						<div class="controls">
							<input type="checkbox" name="em_auth" <?php if($GLOBALS['config']['Email']['Auth']) { echo('checked="checked"'); } ?>/>
						</div>
					</div>
					<div class="control-group">	
						<label class="control-label">Port:</label>
						<div class="controls">
							<input type="text" name="em_port" size="5" value="<?php echo($GLOBALS['config']['Email']['Port']); ?>" title="The SMPT port."/>
						</div>
					</div>
					<div class="control-group">	
						<label class="control-label">Email Username:</label>
						<div class="controls">
							<input type="text" name="em_username" size="30" value="<?php echo($GLOBALS['config']['Email']['Username']); ?>" title="The SMPT Username."/>
						</div>
					</div>
					<div class="control-group">	
						<label class="control-label">Email Password:</label>
						<div class="controls">
							<input type="password" name="em_password" size="30" value="<?php echo($GLOBALS['config']['Email']['Password']); ?>" title="The SMPT Password."/>
						</div>
					</div>
					<div class="control-group">	
						<label class="control-label">Enforce SSL/TLS:</label>
						<div class="controls">
							<input type="radio" name="em_type" value="ssl" <?php if($GLOBALS['config']['Email']['Type'] == "ssl") { echo('checked="checked"'); } ?>/> SSL <br/>
							<input type="radio" name="em_type" value="tls" <?php if($GLOBALS['config']['Email']['Type'] == "tls") { echo('checked="checked"'); } ?>/> TLS
						</div>
					</div>
				</div>
			</div>
			<div id="support" class="tab-pane">
				<script type="text/javascript">
					function addapp(){
						var appName = prompt("Application name: (Protip: use # to create a separator)");
						if(appName == null){
							return;
						}
						$("#apps").append('<option value=' + appName + '>' + appName + '</option>');
						$("#sup_app").val(function( index, value ) {
							if(value == null || value == ""){
								return value + appName;
							} else {
								return value + "," + appName;
							}
						});
					}
					function addos(){
						var osName = prompt("Operating System: (Protip: use # to create a separator)");
						if(osName == null){
							return;
						}
						$("#oss").append('<option value=' + osName + '>' + osName + '</option>');
						$("#sup_os").val(function( index, value ) {
							if(value == null || value == ""){
								return value + osName;
							} else {
								return value + "," + osName;
							}
						});
					}
				</script>
				<h2>Support Settings:</h2>
				<div class="control-group">
					<label class="control-label">Applications:</label>
					<div class="controls">
						<select name="apps" id="apps" multiple><?php foreach ($GLOBALS['config']['Support']['Apps'] as $app) { echo('<option>' . $app . '</option>'); } ?></select><br/>
						<button type="button" onClick="addapp()" class="btn">App New App</button><input type="hidden" name="sup_app" id="sup_app" value="<?php echo(implode(",", $GLOBALS['config']['Support']['Apps'])); ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Operating Systems:</label>
					<div class="controls">
						<select name="oss" id="oss" multiple><?php foreach ($GLOBALS['config']['Support']['OS'] as $oss) { echo('<option>' . $oss . '</option>'); } ?> </select><br/>
						<button type="button" onClick="addos()" class="btn">App New Operating System</button><input type="hidden" name="sup_os" id="sup_os" value="<?php echo(implode(",", $GLOBALS['config']['Support']['OS'])); ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Ticket ID format:</label>
					<div class="controls">
						<input type="text" name="sup_id" value="<?php echo($GLOBALS['config']['Support']['ID']); ?>" class="input-xlarge"/><br/>
						<strong>Syntax:</strong> Use <em>"#"</em> for random number <em>"%"</em> for random letter.
					</div>
				</div>
			</div>
			<div id="database" class="tab-pane">
				<h2>Database Settings:</h2>
				<div class="control-group">
					<label class="control-label">Database Type:</label>
					<div class="controls">
						<select name="db_type" id="db_type">
							<option value="MYSQL" <?php if($GLOBALS['config']['Database']['Type'] == "MYSQL"){echo('selected="selected"');} ?>>MySQL</option>
							<option value="MYSQLI" <?php if($GLOBALS['config']['Database']['Type'] == "MYSQLI"){echo('selected="selected"');} ?> disabled>MySQLi</option>
							<option value="MSSQL" <?php if($GLOBALS['config']['Database']['Type'] == "MSSQL"){echo('selected="selected"');} ?> disabled>Microsoft SQL Server</option>
							<option value="SQLITE" <?php if($GLOBALS['config']['Database']['Type'] == "SQLITE"){echo('selected="selected"');} ?>>SQLite</option>
						</select>
					</div>
				</div>
				<div class="control-group" id="control-group-location">
					<label class="control-label">Database Location:</label>
					<div class="controls">
						<input type="text" name="db_location" size="45" value="<?php echo($GLOBALS['config']['Database']['Location']); ?>" title="The location or URL of your database."/>
					</div>
				</div>
				<div class="control-group" id="control-group-name">
					<label class="control-label">Database Name:</label>
					<div class="controls">
						<input type="text" name="db_name" size="45" value="<?php echo($GLOBALS['config']['Database']['Name']); ?>" title="The name of the database that Lucy will use."/>
					</div>
				</div>
				<div class="control-group" id="control-group-username">
					<label class="control-label">Database Username:</label>
					<div class="controls">
						<input type="text" name="db_username" size="45" value="<?php echo($GLOBALS['config']['Database']['Username']); ?>" title="The username for the database connection."/>
					</div>
				</div>
				<div class="control-group" id="control-group-password">
					<label class="control-label">Database Password:</label>
					<div class="controls">
						<input type="password" name="db_password" size="45" value="<?php echo($GLOBALS['config']['Database']['Password']); ?>" title="The password for the database connection."/>
					</div>
				</div>
				<div class="control-group" id="control-group-unsecured">
					<label class="control-label">Allow unsecured (no password) connections:</label>
					<div class="controls">
						<input type="checkbox" name="db_nullpwd" <?php if($GLOBALS['config']['Database']['nullpwd']){echo('checked="checked"');} ?>/>
					</div>
				</div>
			</div>
			<div id="themes" class="tab-pane">
				<h2>Themes settings:</h2>
				<ul class="thumbnails">
					<li class="span3">
						<div class="thumbnail">
							<img data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;" src="data:image/png;base64,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">
							<div class="caption">
								<h3>Thumbnail label</h3>
								<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
								<p><a href="#" class="btn btn-primary">Action</a> <a href="#" class="btn">Action</a></p>
							</div>
						</div>
					</li>
					<li class="span3">
						<div class="thumbnail">
							<img data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;" src="data:image/png;base64,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">
							<div class="caption">
								<h3>Thumbnail label</h3>
								<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
								<p><a href="#" class="btn btn-primary">Action</a> <a href="#" class="btn">Action</a></p>
							</div>
						</div>
					</li>
					<li class="span3">
						<div class="thumbnail">
							<img data-src="holder.js/300x200" alt="300x200" style="width: 300px; height: 200px;" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAADICAYAAABS39xVAAAI7klEQVR4Xu3bMU8UaxQG4CFEkYIaWmMLHcTEv09BaIydsTYkVNsRQqLem9lkuN9dZ9ldZRbePY8lzsI5z/v5ZnYd9maz2T+dPwQIEAgQ2FNYASkZkQCBuYDCchAIEIgRUFgxURmUAAGF5QwQIBAjoLBiojIoAQIKyxkgQCBGQGHFRGVQAgQUljNAgECMgMKKicqgBAgoLGeAAIEYAYUVE5VBCRBQWM4AAQIxAgorJiqDEiCgsJwBAgRiBBRWTFQGJUBAYTkDBAjECCismKgMSoCAwnIGCBCIEVBYMVEZlAABheUMECAQI6CwYqIyKAECCssZIEAgRkBhxURlUAIEFJYzQIBAjIDCionKoAQIKCxngACBGAGFFROVQQkQUFjOAAECMQIKKyYqgxIgoLCcAQIEYgQUVkxUBiVAQGE5AwQIxAgorJioDEqAgMJyBggQiBFQWDFRGZQAAYXlDBAgECOgsGKiMigBAgrLGSBAIEZAYcVEZVACBBSWM0CAQIyAwoqJyqAECCgsZ4AAgRgBhRUTlUEJEFBYzgABAjECCismKoMSIKCwnAECBGIEFFZMVAYlQEBhOQMECMQIKKyYqAxKgIDCcgYIEIgRUFgxURmUAAGF5QwQIBAjoLBiojIoAQIKyxkgQCBGQGHFRGVQAgQUljNAgECMgMKKicqgBAgoLGeAAIEYAYUVE5VBCRBQWM4AAQIxAgorJiqDEiCgsJwBAgRiBBRWTFQGJUBAYTkDBAjECCismKgMSoCAwnIGCBCIEVBYMVEZlAABheUMECAQI6CwYqIyKAECCssZIEAgRkBhxURlUAIEFJYzQIBAjIDCionKoAQIKCxngACBGAGFFROVQQkQUFjOAAECMQIKKyYqgxIgoLCcAQIEYgQUVkxUBiVAQGE5AwQIxAgorJioDEqAgMJyBggQiBFQWDFRGZQAAYXlDBAgECOgsGKiMigBAgrLGSBAIEZAYcVEZVACBBSWM0CAQIyAwoqJyqAECCgsZ4AAgRgBhRUTlUEJEFBYzgABAjECCismqqcH/fXrV3d9fd3d3d09Xnh8fNydnZ2NvvDz58/dbDZ79mvX5fzx40d3eXnZ/fz58/Ele3t73adPn7rDw8Pfvs1Lz7vuXq6bVkBhTeu7le8+9o9/+MFHR0fdx48fH+cYK7bnuHaTRb9//959+/Zt6UtOT0+7k5OT+d+/hnk32c210woorGl9t/Ldv3792t3c3Mx/1vv377sPHz50Y1/r/779+lAMbYEMr9/02nUXbQuovaPq7/a+fPkyL6h3797NS/bNmzcvPu+6e7luOwIKazvOk/2UtgDaf+htAQwl1F7b3nmNfX2Ta5eVzVg59SU0vBVcvPsb3vYNRXZwcPD4Nvc5550sDN94cgGFNTnxy/yA9jOf4U6qfeu4+PnWcP1Qen1pDMWy6trFO6GhIJfduS0TWSysttxWzbDpvC+Tip/6twIK628FX9nrFz8faj8Pau+ElhXAcHdzf3//+BZt1bX9h+SLd3rn5+fd1dXV/EP19s5vGVdbpsPd1JTzvrLYjLOmgMJaEyrlsvYzqn7m9q3U1AXQfv/+7dzDw8OcrS3NVXdX7fVTz5uSqTn/E1BYO3oaxv6xb6MANnn8YKBvX9PezW1j3h2Nf2fXUlg7G23XLX4mtMnbvE2ubZ+baktmnburZWXVv1Zh7fDh/MPVFNYfwiW8bNsfYo89M/XU51dPlVXvO+V/EiTkZ8bfBRRW+KlY5y5kKI39/f3HxwTaIln1WMOqawfC9vOz/sHP29vb+V+NPXHfllX77Fcbx7JHNp5r3vDoS46vsMJjX7yrGXsYtC2MqR4cbYuz/6D/4uLif78q1H7w3s6w+CzWYhxTzRsee9nxFdYORL/4uVG70uJbsil+1WWdp9eHYnrq14iGudsn4KeYdwciL7uCwtqR6MeKYFu//Nw++7Xsma2euX/r9/bt2yd/j7C/buyXoDf538dNrt2R+MusobDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QIKKz9DGxAoI6CwykRtUQL5AgorP0MbECgjoLDKRG1RAvkCCis/QxsQKCOgsMpEbVEC+QL/Ag1TKDpTl19vAAAAAElFTkSuQmCC">
							<div class="caption">
								<h3>Thumbnail label</h3>
								<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
								<p><a href="#" class="btn btn-primary">Action</a> <a href="#" class="btn">Action</a></p>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div id="reCAP" class="tab-pane">
				<h2>reCAPTCHA Settings:</h2>
				<div class="control-group">
					<label class="control-label">Enable reCAPTCHA:</label>
					<div class="controls">
						<input type="checkbox" name="cap_enable" <?php if($GLOBALS['config']['ReCaptcha']['Enable']){echo('checked="checked"');} ?>/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Public Key:</label>
					<div class="controls">
						<input type="text" name="cap_public" size="75" value="<?php echo($GLOBALS['config']['ReCaptcha']['Public']); ?>" title="Your reCAPTCHA public key."/> <a href="http://www.google.com/recaptcha" target="_blank">Get Key</a>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Private Key:</label>
					<div class="controls">
						<input type="text" name="cap_private" size="75" value="<?php echo($GLOBALS['config']['ReCaptcha']['Private']); ?>" title="Your reCAPTCHA private key."/> <a href="http://www.google.com/recaptcha" target="_blank">Get Key</a>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Show Captcha when Creating an Account:</label>
					<div class="controls">
						<input type="checkbox" name="cap_signup" <?php if($GLOBALS['config']['ReCaptcha']['Signup']){echo('checked="checked"');} ?>/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Show Captcha when logging in:</label>
					<div class="controls">
						<input type="checkbox" name="cap_login" <?php if($GLOBALS['config']['ReCaptcha']['Login']){echo('checked="checked"');} ?>/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Show Captcha when Creating a Ticket:</label>
					<div class="controls">
						<input type="checkbox" name="cap_ticket" <?php if($GLOBALS['config']['ReCaptcha']['Ticket']){echo('checked="checked"');} ?>/>
					</div>
				</div>
			</div>
			<div id="imgur" class="tab-pane">
				<h2>Imgur Settings:</h2>
				<div class="control-group">
					<label class="control-label">Enable Imgur upload:</label>
					<div class="controls">
						<input type="checkbox" name="img_enable" <?php if($GLOBALS['config']['Imgur']['Enable']){echo('checked="checked"');} ?>/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Imgur API key:</label>
					<div class="controls">
						<input type="text" name="img_key" size="75" value="<?php echo($GLOBALS['config']['Imgur']['Key']); ?>" title="Your Imgur API key."/> <a href="http://api.imgur.com/#register" target="_blank">Get Key</a>
					</div>
				</div>
			</div>
			<div id="akismet" class="tab-pane">
				<h2>Akismet Settings:</h2>
				<div class="control-group">
					<label class="control-label">Enable Akismet:</label>
					<div class="controls">
						<input type="checkbox" name="aki_enable" <?php if($GLOBALS['config']['Akismet']['Enable']){echo('checked="checked"');} ?>/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Akismet API Key:</label>
					<div class="controls">
						<input type="text" name="aki_key" size="75" value="<?php echo($GLOBALS['config']['Akismet']['Key']); ?>" title="Your Akismet API key."/> <a href="https://akismet.com/signup/" target="_blank">Get Key</a>
					</div>
				</div>
			</div>
		</div>
		<hr/>
		<input type="submit" name="submit" value="Save Changes" class="btn btn-primary"/> <input type="reset" name="reset" value="Rest Settings to Default" class="btn"/>
	</form>
<script type="text/javascript">
$('#db_type').change(function() {
  if(document.getElementsByName("db_type")[0].value == "SQLITE"){
		$('#control-group-location').hide();
		$('#control-group-username').hide();
		$('#control-group-password').hide();
		$('#control-group-unsecured').hide();

	} else {
		$('#control-group-location').show();
		$('#control-group-username').show();
		$('#control-group-password').show();
		$('#control-group-unsecured').show();
	}
});

if(document.getElementsByName("db_type")[0].value == "SQLITE"){
	$('#control-group-location').hide();
	$('#control-group-username').hide();
	$('#control-group-password').hide();
	$('#control-group-unsecured').hide();

} else {
	$('#control-group-location').show();
	$('#control-group-username').show();
	$('#control-group-password').show();
	$('#control-group-unsecured').show();
}
</script>
<?php getFooter(); ?>