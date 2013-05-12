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
		// Downloads the default settings from the Repo
		$defaults = file_get_contents('https://github.com/ecnepsnai/Lucy/blob/master/lucy-config/config.json');
		file_put_contents('../../lucy-config/config.json', $defaults);
		$notice = True;
	}

	getHeader("Settings");
	getSidebar(4);
?>
		<div id="content">
			<script>
				$(function() {
					$("#tabs").tabs();
				});
			</script>
			<form name="lcystns" method="post">
				<div id="tabs">
					<ul>
						<li><a href="#general">General</a></li>
						<li><a href="#database">Database</a></li>
						<li><a href="#reCAP">reCAPTCHA</a></li>
						<li><a href="#imgur">Imgur</a></li>
						<li><a href="#akismet">Akismet</a></li>
					</ul>
					<div id="general">
						<h2>General Settings:</h2>
						<table>
							<tr>
								<td>
									Domain Address:<br/>
									<input type="url" name="domain" size="45" value="<?php echo($GLOBALS['config']['Domain']); ?>" title="This is the URL that Lucy is located in.  Used for linking resources."/>
								</td>
							</tr>
							<tr>
								<td>
									Session Expiration Time:<br/>
									<input type="text" name="expire" size="30" value="<?php echo($GLOBALS['config']['SessionExpire']); ?>" title="The length of time a user has to be inactive until they will be logged out.  In milliseconds."/>
								</td>
							</tr>
							<tr>
								<td>
									Enable Debug Mode: <input type="checkbox" name="debug" <?php if($GLOBALS['config']['Debug']){echo('checked="checked"');} ?> title="Debug mode enabled the output of debug information on certain libraries."/><br/>
									<em>Debug mode enabled the output of debug information on certain libraries.</em>
								</td>
							</tr>
							<tr>
								<td>
									Main Title:<br/>
									<input type="text" name="txt_main" size="30" value="<?php echo($GLOBALS['config']['Strings']['Main']); ?>" title="This appears in the title and throughout the application."/>
								</td>
							</tr>
							<tr>
								<td>
									Title Separator:<br/>
									<input type="text" name="txt_sep" size="5" maxlength="5" value="<?php echo($GLOBALS['config']['Strings']['Separator']); ?>" title="This appears in the title.  Trailing and Leading whitespace is automatically added."/>
								</td>
							</tr>
							<tr>
								<td>
									Footer Text:<br/>
									<input type="text" name="txt_ftr" size="30" value="<?php echo($GLOBALS['config']['Strings']['Footer']); ?>" title="The text that appears on the bottom left of the page."/>
								</td>
							</tr>
							<tr>
								<td>
									Email Name:<br/>
									<input type="text" name="em_name" size="30" value="<?php echo($GLOBALS['config']['Email']['Name']); ?>" title="The email name used when Lucy sends out automatic emails to users."/>
								</td>
							</tr>
							<tr>
								<td>
									Email Address:<br/>
									<input type="email" name="em_address" size="30" value="<?php echo($GLOBALS['config']['Email']['Address']); ?>" title="The email address used when Lucy sends out automatic emails to users."/>
								</td>
							</tr>
						</table>
					</div>
					<div id="database">
						<h2>Database Settings:</h2>
						<table>
							<tr>
								<td>
									Database Type:<br/>
									<select name="db_type">
										<option value="MYSQL" <?php if($GLOBALS['config']['Database']['Type'] == "MYSQL"){echo('selected="selected"');} ?>>MySQL</option>
										<option value="MYSQLI" <?php if($GLOBALS['config']['Database']['Type'] == "MYSQLI"){echo('selected="selected"');} ?>>MySQLi</option>
										<option value="MSSQL" <?php if($GLOBALS['config']['Database']['Type'] == "MSSQL"){echo('selected="selected"');} ?>>Microsoft SQL Server</option>
										<option value="SQLITE" <?php if($GLOBALS['config']['Database']['Type'] == "SQLITE"){echo('selected="selected"');} ?>>SQLite</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									Database Location:<br/>
									<input type="text" name="db_location" size="45" value="<?php echo($GLOBALS['config']['Database']['Location']); ?>" title="The location or URL of your database."/>
								</td>
							</tr>
							<tr>
								<td>
									Database Name:<br/>
									<input type="text" name="db_name" size="45" value="<?php echo($GLOBALS['config']['Database']['Name']); ?>" title="The name of the database that Lucy will use."/>
								</td>
							</tr>
							<tr>
								<td>
									Database Username:<br/>
									<input type="text" name="db_username" size="45" value="<?php echo($GLOBALS['config']['Database']['Username']); ?>" title="The username for the database connection."/>
								</td>
							</tr>
							<tr>
								<td>
									Database Password:<br/>
									<input type="password" name="db_password" size="45" value="<?php echo($GLOBALS['config']['Database']['Password']); ?>" title="The password for the database connection."/>
								</td>
							</tr>
							<tr>
								<td>
									Allow unsecured (no password) connections: <input type="checkbox" name="db_nullpwd" <?php if($GLOBALS['config']['Database']['nullpwd']){echo('checked="checked"');} ?>/>
								</td>
							</tr>
						</table>
					</div>
					<div id="reCAP">
						<h2>reCAPTCHA Settings:</h2>
						<table>
							<tr>
								<td>
									Enable reCAPTCHA: <input type="checkbox" name="cap_enable" <?php if($GLOBALS['config']['ReCaptcha']['Enable']){echo('checked="checked"');} ?>/>
								</td>
							</tr>
							<tr>
								<td>
									Public Key:<br/>
									<input type="text" name="cap_public" size="75" value="<?php echo($GLOBALS['config']['ReCaptcha']['Public']); ?>" title="Your reCAPTCHA public key."/> <a href="http://www.google.com/recaptcha">Get Key</a>
								</td>
							</tr>
							<tr>
								<td>
									Private Key:<br/>
									<input type="text" name="cap_private" size="75" value="<?php echo($GLOBALS['config']['ReCaptcha']['Private']); ?>" title="Your reCAPTCHA private key."/> <a href="http://www.google.com/recaptcha">Get Key</a>
								</td>
							</tr>
							<tr>
								<td>
									Show Captcha when Creating an Account: <input type="checkbox" name="cap_signup" <?php if($GLOBALS['config']['ReCaptcha']['Signup']){echo('checked="checked"');} ?>/>
								</td>
							</tr>
							<tr>
								<td>
									Show Captcha when logging in: <input type="checkbox" name="cap_login" <?php if($GLOBALS['config']['ReCaptcha']['Login']){echo('checked="checked"');} ?>/>
								</td>
							</tr>
							<tr>
								<td>
									Show Captcha when Creating a Ticket: <input type="checkbox" name="cap_ticket" <?php if($GLOBALS['config']['ReCaptcha']['Ticket']){echo('checked="checked"');} ?>/>
								</td>
							</tr>
						</table>
					</div>
					<div id="imgur">
						<h2>Imgur Settings:</h2>
						<table>
							<tr>
								<td>
									Enable Imgur upload: <input type="checkbox" name="img_enable" <?php if($GLOBALS['config']['Imgur']['Enable']){echo('checked="checked"');} ?>/>
								</td>
							</tr>
							<tr>
								<td>
									Imgur API key:<br/>
									<input type="text" name="img_key" size="75" value="<?php echo($GLOBALS['config']['Imgur']['Key']); ?>" title="Your Imgur API key."/> <a href="http://api.imgur.com/#register">Get Key</a>
								</td>
							</tr>
						</table>
					</div>
					<div id="akismet">
						<h2>Akismet Settings:</h2>
						<table>
							<tr>
								<td>
									Enable Akismet: <input type="checkbox" name="aki_enable" <?php if($GLOBALS['config']['Akismet']['Enable']){echo('checked="checked"');} ?>/>
								</td>
							</tr>
							<tr>
								<td>
									Akismet API Key:<br/>
									<input type="text" name="aki_key" size="75" value="<?php echo($GLOBALS['config']['Akismet']['Key']); ?>" title="Your Akismet API key."/> <a href="https://akismet.com/signup/">Get Key</a>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="buttons">
					<input type="submit" name="submit" value="Save Changes"/> <input type="reset" name="reset" value="Rest Settings to Default"/>
				</div>
			</form>
		</div>
	</div>
	<?php getFooter(); ?>
</div>