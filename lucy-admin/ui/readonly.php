<?php
	require("../session.php");
	require("../cda.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	/* THE TESTS */
	function performTest(){
		$testResults = array("1"=>false,"2"=>false,"3"=>false,"4"=>false,"5"=>false,"6"=>false);

		/* Test 1: Initial DB configuration */
		if(!empty($GLOBALS['config']['Database']['Type']) && !empty($GLOBALS['config']['Database']['Name'])){
			$testResults["1"] = true;
		}
endTest1:

		/* Setting up CDA */
		if($testResults["1"] === true){
			$cda = new cda;
			$cda->init($GLOBALS['config']['Database']['Type']);
		}

		/* Test 2: Initial User configuration */
		if($testResults["1"] === true){
			$response = array();
			try{
				$response = $cda->select(array("type"),"userlist",array("id"=>"1"));
			} catch(Exception $e){
				goto endTest2;
			}
			if($response['data']['type'] === 'Admin'){
				$testResults["2"] = true;
			}
		}
endTest2:

		/* Test 3: Added additional fields */
		if(!empty($GLOBALS['designer']['config'])){
			$testResults["3"] = true;
		}
endTest3:

		/* Test 4: Verify Admin email */
		if($testResults["1"] === true){
			$response = array();
			try{
				$response = $cda->select(array("verified"),"userlist",array("id"=>"2"));
			} catch(Exception $e){
				goto endTest4;
			}
			if($response['data']['verified'] === '1'){
				$testResults["4"] = true;
			}
		}
endTest4:

		/* Test 5: Verify Uploads permission */
		if(is_writable('../../lucy-content/uploads')){
			$testResults["5"] = true;
		}
endTest5:

		/* Test 6: Verify Lucybot Configuration */
		if(!empty($GLOBALS['config']['Email']['Address']) && !empty($GLOBALS['config']['Email']['Name'])){
			$testResults["6"] = true;
		}
endTest6:

		return $testResults;
	}

	// User chose to make Lucy public.
	if(isset($_POST['submit'])){
		$GLOBALS['config']['ReadOnly'] = false;
		$json = '{"config":' . json_encode($GLOBALS['config']) . '}';
		file_put_contents('../../lucy-config/config.json', $json);
		header("location: index.php");
	}
	getHeader("Preflight Checklist");
	getNav(9999);?>
<h1>Preflight Checklist</h1>
<p>Below are recommended actions to take before making Lucy public, completing the steps below will make sure your users get the best possible experience when using Lucy.</p>
<table class="table table-bordered">
	<thead>
		<tr>
			<th></th>
			<th>Step</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$testResults = performTest();
		foreach ($testResults as $test => $pass) {
			switch ($test) {
				case '1':
					if($pass === true){ ?>
<tr class="success"> <td><span class="glyphicon glyphicon-ok"></span></td> <td>Initial database configuration</td> <td><a href="//ianspence.com/support/docs/lucy/setup" target="_blank">Help</a></td> </tr>
					<?php } else { ?>
<tr class="error"> <td><span class="glyphicon glyphicon-remove"></span></td> <td>Initial database configuration</td> <td><a href="//ianspence.com/support/docs/lucy/setup" target="_blank">Help</a></td> </tr>
					<?php }
					break;
				case '2':
					if($pass === true){ ?>
<tr class="success"> <td><span class="glyphicon glyphicon-ok"></span></td> <td>Initial user configuration</td> <td><a href="//ianspence.com/support/docs/lucy/setup" target="_blank">Help</a></td> </tr>
					<?php } else { ?>
<tr class="error"> <td><span class="glyphicon glyphicon-remove"></span></td> <td>Initial user configuration</td> <td><a href="//ianspence.com/support/docs/lucy/setup" target="_blank">Help</a></td> </tr>
					<?php }
					break;
				case '3':
					if($pass === true){ ?>
<tr class="success"> <td><span class="glyphicon glyphicon-ok"></span></td> <td>Added additional fields</td> <td><a href="//ianspence.com/support/docs/lucy/designer" target="_blank">Help</a></td> </tr>
					<?php } else { ?>
<tr class="error"> <td><span class="glyphicon glyphicon-remove"></span></td> <td>Added additional fields</td> <td><a href="designer.php">Configure</a> - <a href="//ianspence.com/support/docs/lucy/designer" target="_blank">Help</a></td> </tr>
					<?php }
					break;
				case '4':
					if($pass === true){ ?>
<tr class="success"> <td><span class="glyphicon glyphicon-ok"></span></td> <td>Verified administrator email address</td> <td><a href="//ianspence.com/support/docs/lucy/email" target="_blank">Help</a></td> </tr>
					<?php } else { ?>
<tr class="error"> <td><span class="glyphicon glyphicon-remove"></span></td> <td>Verified administrator email address</td> <td><a href="../../email_verify.php">Configure</a> - <a href="//ianspence.com/support/docs/lucy/email" target="_blank">Help</a></td> </tr>
					<?php }
					break;
				case '5':
					if($pass === true){ ?>
<tr class="success"> <td><span class="glyphicon glyphicon-ok"></span></td> <td>Grant Lucy access to uploads folder</td> <td><a href="//ianspence.com/support/docs/lucy/uploads" target="_blank">Help</a></td> </tr>
					<?php } else { ?>
<tr class="error"> <td><span class="glyphicon glyphicon-remove"></span></td> <td>Grant Lucy access to uploads folder</td> <td><a href="//ianspence.com/support/docs/lucy/uploads" target="_blank">Help</a></td> </tr>
					<?php }
					break;
				case '6':
					if($pass === true){ ?>
<tr class="success"> <td><span class="glyphicon glyphicon-ok"></span></td> <td>Configured Lucy email bot</td> <td><a href="//ianspence.com/support/docs/lucy/email" target="_blank">Help</a></td> </tr>
					<?php } else { ?>
<tr class="error"> <td><span class="glyphicon glyphicon-remove"></span></td> <td>Configured Lucy email bot</td> <td><a href="settings.php">Configure</a> - <a href="//ianspence.com/support/docs/lucy/email" target="_blank">Help</a></td> </tr>
					<?php }
					break;
			}
		} ?>
	</tbody>
</table>
<h3>When you are ready...</h3>
<form method="post"><input name="submit" type="submit" class="btn btn-large btn-primary" value="Make Lucy Public" <?php if($GLOBALS['config']['ReadOnly'] === false){ echo('disabled'); } ?>/></form>
<?php getFooter(); ?>
