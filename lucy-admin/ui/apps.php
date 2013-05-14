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
		$apps = array();
		$apps = explode(",", $_POST['aph']);
		$GLOBALS['config']['Apps'] = $apps;
		$json = '{"config":' . json_encode($GLOBALS['config']) . '}';
		file_put_contents('../../lucy-config/config.json', $json);
		$notice = True;
	}

	getHeader("Apps");
	getSidebar(4);
?>
<script type="text/javascript">
function addapp(){
	var appName = prompt("Application name: ");
	$("#apps").append('<option value=' + appName + '>' + appName + '</option>');
	$("#aph").val(function( index, value ) {
		if(value == null || value == ""){
			return value + appName;
		} else {
			return value + "," + appName;
		}
	});
}
</script>
		<div id="content">
			<h2>Supported Apps</h2>
			<select name="apps" id="apps" multiple>
				<?php
					foreach ($GLOBALS['config']['Apps'] as $app) {
						echo('<option>' . $app . '</option>');
					}
				?>
			</select>
			<div id="buttons">
				<button onClick="addapp()">App New App</button> <form method="post" style="display:inline"><input type="hidden" name="aph" id="aph" value="<?php echo(implode(",", $GLOBALS['config']['Apps'])); ?>"/><input type="submit" name="submit" value="Save Changes" /></form>
			</div>
		</div>
	</div>
	<?php getFooter(); ?>
</div>