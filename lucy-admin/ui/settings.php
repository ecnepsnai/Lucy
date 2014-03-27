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
		$GLOBALS['config']['Support']['ID'] = $_POST['sup_id'];

		// Getting the appearance settings:
		$GLOBALS['config']['Strings']['Main'] = $_POST['txt_main'];
		$GLOBALS['config']['Strings']['Separator'] = $_POST['txt_sep'];
		$GLOBALS['config']['Strings']['Footer'] = $_POST['txt_ftr'];
		$GLOBALS['config']['Strings']['Home']['Title'] = $_POST['txt_wlc_title'];
		$GLOBALS['config']['Strings']['Home']['Slogan'] = $_POST['txt_wlc_slogan'];

		// Getting the Email settings:
		$GLOBALS['config']['Email']['Name'] = $_POST['em_name'];
		$GLOBALS['config']['Email']['Address'] = $_POST['em_address'];
		$GLOBALS['config']['Email']['Footer'] = $_POST['em_footer'];

		// Getting the Database Settings:
		$GLOBALS['config']['Database']['Type'] = $_POST['db_type'];
		$GLOBALS['config']['Database']['Location'] = $_POST['db_location'];
		$GLOBALS['config']['Database']['Name'] = $_POST['db_name'];
		$GLOBALS['config']['Database']['Username'] = $_POST['db_username'];
		$GLOBALS['config']['Database']['Password'] = $_POST['db_password'];

		// Gettings the Image Settings:
		if($_POST['img_enable'] == "on"){
			$GLOBALS['config']['Images']['Enable'] = True;
		} else {
			$GLOBALS['config']['Images']['Enable'] = False;
		}

		$json = '{"config":' . json_encode($GLOBALS['config']) . '}';

		file_put_contents('../../lucy-config/config.json', $json);
		$notice = True;
	}

	if(isset($_POST['reset'])){
		file_put_contents('../../lucy-config/config.json', '');
		$notice = True;
	}

	getHeader("Settings");
	getNav(6);
?>
<form method="post">
<div class="row">
	<div class="col-md-3">
		<ul class="nav nav-pills nav-stacked">
			<li class="active"><a href="#general" data-toggle="tab">General</a></li>
			<li><a href="#appearance" data-toggle="tab">Appearance</a></li>
			<li><a href="#email" data-toggle="tab">Email</a></li>
			<li><a href="#database" data-toggle="tab">Database</a></li>
		</ul>
		<hr/>
		<input type="submit" name="submit" value="Save Changes" class="btn btn-success"/>
	</div>
	<div class="col-md-8">
		<div class="tabbable">
			<div class="form-horizontal">
				<div class="tab-content">
					<div id="general" class="tab-pane active">
						<h2>General Settings:</h2>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Session Expiration Time:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="expire" size="30" value="<?php echo($GLOBALS['config']['SessionExpire']); ?>" title="The length of time a user has to be inactive until they will be logged out.  In milliseconds."/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Thread ID format:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="sup_id" value="<?php echo($GLOBALS['config']['Support']['ID']); ?>" class="input-xlarge"/>
								<p class="help-block"><strong>Syntax:</strong> Use <em>"#"</em> for random number <em>"%"</em> for random letter.</p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"></label>
							<div class="col-sm-7">
								<label class="checkbox"><input type="checkbox" name="img_enable" <?php if($GLOBALS['config']['Images']['Enable'] === true){ ?>checked<?php } ?>> Allow Image Uploads</label>
							</div>
						</div>
					</div>
					<div id="appearance" class="tab-pane">
						<h2>Appearance Settings:</h2>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Main Title:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="txt_main" size="30" value="<?php echo($GLOBALS['config']['Strings']['Main']); ?>" title="This appears in the title and throughout the application."/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Title Separator:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="txt_sep" size="5" maxlength="5" value="<?php echo($GLOBALS['config']['Strings']['Separator']); ?>" title="This appears in the title. Don't forget Trailing and Leading whitespace."/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Footer Text:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="txt_ftr" size="30" value="<?php echo($GLOBALS['config']['Strings']['Footer']); ?>" title="The text that appears on the bottom left of the page."/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Welcome Page Title:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="txt_wlc_title" size="30" value="<?php echo($GLOBALS['config']['Strings']['Home']['Title']); ?>" title="The text that appears at the home page"/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Welcome Page Slogan:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="txt_wlc_slogan" size="30" value="<?php echo($GLOBALS['config']['Strings']['Home']['Slogan']); ?>" title="The text that appears at the home page below the title"/>
							</div>
						</div>
					</div>
					<div id="email" class="tab-pane">
						<h2>Email Settings</h2>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Email Name:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="em_name" size="30" value="<?php echo($GLOBALS['config']['Email']['Name']); ?>" title="The email name used when Lucy sends out automatic emails to users."/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Email Address:</label>
							<div class="col-sm-7">
								<input class="form-control" type="email" name="em_address" size="30" value="<?php echo($GLOBALS['config']['Email']['Address']); ?>" title="The email address used when Lucy sends out automatic emails to users."/>
							</div>
						</div>
						<div class="form-group">	
							<label class="col-sm-4 control-label">Email Footer:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="em_footer" size="30" value="<?php echo($GLOBALS['config']['Email']['Footer']); ?>" title="The footer appended to the bottom of emails sent by Lucy"/>
							</div>
						</div>
						<em>Lucy uses the mail program configured in php.ini. <a href="http://www.php.net/manual/en/mail.configuration.php" target="_blank">Help</a></em>
					</div>
					<div id="database" class="tab-pane">
						<h2>Database Settings:</h2>
						<div class="form-group">
							<label class="col-sm-4 control-label">Database Type:</label>
							<div class="col-sm-7">
								<select class="form-control" name="db_type" id="db_type">
									<option value="MYSQL" <?php if($GLOBALS['config']['Database']['Type'] == "MYSQL"){echo('selected="selected"');} ?>>MySQL</option>
									<option value="MYSQLI" <?php if($GLOBALS['config']['Database']['Type'] == "MYSQLI"){echo('selected="selected"');} ?>>MySQLi</option>
									<option value="SQLITE" <?php if($GLOBALS['config']['Database']['Type'] == "SQLITE"){echo('selected="selected"');} ?>>SQLite</option>
								</select>
							</div>
						</div>
						<div class="form-group" id="control-group-location">
							<label class="col-sm-4 control-label">Database Location:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="db_location" size="45" value="<?php echo($GLOBALS['config']['Database']['Location']); ?>" title="The location or URL of your database."/>
							</div>
						</div>
						<div class="form-group" id="control-group-name">
							<label class="col-sm-4 control-label">Database Name:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="db_name" size="45" value="<?php echo($GLOBALS['config']['Database']['Name']); ?>" title="The name of the database that Lucy will use."/>
							</div>
						</div>
						<div class="form-group" id="control-group-username">
							<label class="col-sm-4 control-label">Database Username:</label>
							<div class="col-sm-7">
								<input class="form-control" type="text" name="db_username" size="45" value="<?php echo($GLOBALS['config']['Database']['Username']); ?>" title="The username for the database connection."/>
							</div>
						</div>
						<div class="form-group" id="control-group-password">
							<label class="col-sm-4 control-label">Database Password:</label>
							<div class="col-sm-7">
								<input class="form-control" type="password" name="db_password" size="45" value="<?php echo($GLOBALS['config']['Database']['Password']); ?>" title="The password for the database connection."/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>
<?php getFooter(); ?>
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