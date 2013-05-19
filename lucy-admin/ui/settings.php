<?php
	require("../session.php");
	require("../sql.php");
	require("default.php");

	// Administrator access only.
	if(!$usr_Type == "Admin"){
		die("Forbidden.");
	}

	// User chose to save the settings.
	if(isset($_POST['submit'])){
		// Getting the general settings:
		$GLOBALS['config']['Domain'] = $_POST['domain'];
		$GLOBALS['config']['SessionExpire'] = $_POST['expire'];
		$GLOBALS['config']['Debug'] = $_POST['debug'];
		$GLOBALS['config']['Email']['Name'] = $_POST['em_name'];
		$GLOBALS['config']['Email']['Address'] = $_POST['em_address'];

		// Getting the Support settings:
		$GLOBALS['config']['Apps'] = explode(",", $_POST['aph']);
		$GLOBALS['config']['OS'] = explode(",", $_POST['osh']);

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

		// Getting the Imgur Settings:
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
	getNav(4);
?>
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab">General</a></li>
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
					<label class="control-label">Domain Address:</label>
					<div class="controls">
						<input type="url" name="domain" size="45" value="<?php echo($GLOBALS['config']['Domain']); ?>" title="This is the URL that Lucy is located in.  Used for linking resources."/>
					</div>
				</div>
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
						<input type="text" name="txt_sep" size="5" maxlength="5" value="<?php echo($GLOBALS['config']['Strings']['Separator']); ?>" title="This appears in the title.  Trailing and Leading whitespace is automatically added."/>
					</div>
				</div>
				<div class="control-group">	
					<label class="control-label">Footer Text:</label>
					<div class="controls">
						<input type="text" name="txt_ftr" size="30" value="<?php echo($GLOBALS['config']['Strings']['Footer']); ?>" title="The text that appears on the bottom left of the page."/>
					</div>
				</div>
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
			</div>
			<div id="support" class="tab-pane">
				<script type="text/javascript">
					function addapp(){
						var appName = prompt("Application name: (Protip: use # to create a separator)");
						if(appName == null){
							return;
						}
						$("#apps").append('<option value=' + appName + '>' + appName + '</option>');
						$("#aph").val(function( index, value ) {
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
						$("#osh").val(function( index, value ) {
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
						<select name="apps" id="apps" multiple><?php foreach ($GLOBALS['config']['Apps'] as $app) { echo('<option>' . $app . '</option>'); } ?></select><br/>
						<button type="button" onClick="addapp()">App New App</button><input type="hidden" name="aph" id="aph" value="<?php echo(implode(",", $GLOBALS['config']['Apps'])); ?>"/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Operating Systems:</label>
					<div class="controls">
						<select name="oss" id="oss" multiple><?php foreach ($GLOBALS['config']['OS'] as $oss) { echo('<option>' . $oss . '</option>'); } ?> </select><br/>
						<button type="button" onClick="addos()">App New Operating System</button><input type="hidden" name="osh" id="osh" value="<?php echo(implode(",", $GLOBALS['config']['OS'])); ?>"/>
					</div>
				</div>
			</div>
			<div id="database" class="tab-pane">
				<h2>Database Settings:</h2>
				<div class="control-group">
					<label class="control-label">Database Type:</label>
					<div class="controls">
						<select name="db_type">
							<option value="MYSQL" <?php if($GLOBALS['config']['Database']['Type'] == "MYSQL"){echo('selected="selected"');} ?>>MySQL</option>
							<option value="MYSQLI" <?php if($GLOBALS['config']['Database']['Type'] == "MYSQLI"){echo('selected="selected"');} ?>>MySQLi</option>
							<option value="MSSQL" <?php if($GLOBALS['config']['Database']['Type'] == "MSSQL"){echo('selected="selected"');} ?>>Microsoft SQL Server</option>
							<option value="SQLITE" <?php if($GLOBALS['config']['Database']['Type'] == "SQLITE"){echo('selected="selected"');} ?>>SQLite</option>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Database Location:</label>
					<div class="controls">
						<input type="text" name="db_location" size="45" value="<?php echo($GLOBALS['config']['Database']['Location']); ?>" title="The location or URL of your database."/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Database Name:</label>
					<div class="controls">
						<input type="text" name="db_name" size="45" value="<?php echo($GLOBALS['config']['Database']['Name']); ?>" title="The name of the database that Lucy will use."/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Database Username:</label>
					<div class="controls">
						<input type="text" name="db_username" size="45" value="<?php echo($GLOBALS['config']['Database']['Username']); ?>" title="The username for the database connection."/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Database Password:</label>
					<div class="controls">
						<input type="password" name="db_password" size="45" value="<?php echo($GLOBALS['config']['Database']['Password']); ?>" title="The password for the database connection."/>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Allow unsecured (no password) connections:</label>
					<div class="controls">
						<input type="checkbox" name="db_nullpwd" <?php if($GLOBALS['config']['Database']['nullpwd']){echo('checked="checked"');} ?>/>
					</div>
				</div>
			</div>
			<div id="themes" class="tab-pane">
				<h2>Themes support coming soon...</h2>
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
						<input type="text" name="cap_public" size="75" value="<?php echo($GLOBALS['config']['ReCaptcha']['Public']); ?>" title="Your reCAPTCHA public key."/> <a href="http://www.google.com/recaptcha">Get Key</a>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Private Key:</label>
					<div class="controls">
						<input type="text" name="cap_private" size="75" value="<?php echo($GLOBALS['config']['ReCaptcha']['Private']); ?>" title="Your reCAPTCHA private key."/> <a href="http://www.google.com/recaptcha">Get Key</a>
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
						<input type="text" name="img_key" size="75" value="<?php echo($GLOBALS['config']['Imgur']['Key']); ?>" title="Your Imgur API key."/> <a href="http://api.imgur.com/#register">Get Key</a>
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
						<input type="text" name="aki_key" size="75" value="<?php echo($GLOBALS['config']['Akismet']['Key']); ?>" title="Your Akismet API key."/> <a href="https://akismet.com/signup/">Get Key</a>
					</div>
				</div>
			</div>
		</div>
		<hr/>
		<input type="submit" name="submit" value="Save Changes" class="btn btn-primary"/> <input type="reset" name="reset" value="Rest Settings to Default" class="btn"/>
	</form>
<?php getFooter(); ?>