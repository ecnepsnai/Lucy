<?php
	require("assets/lib/session.php");

	// This page requires a user to be signed in.
	if(!$usr_IsSignedIn){
		require("error_auth.php");
	}

	require("assets/lib/sql.php");

	$sql = "SELECT * FROM userlist WHERE id = '" . $usr_ID . "'";

	try {
		$user = sqlQuery($sql, True);
	} catch (Exception $e) {
		die($e);
	}

	if(count($user) == 0){
		documentCreate(TITLE_ERROR, False); ?>
		<div id="wrapper">
		<?php writeHeader(); ?>
		<div id="content">
			<div class="notice" id="yellow">
				<strong>User not found!</strong><br/>
				Uh oh!  That user was not found.  This isn't a good thing.
			</div>
		</div>
		<?php writeFooter(); ?></div><?php die();  
	}

	documentCreate("Your Profile", True); ?>
	<div id="wrapper">
		<?php writeHeader(); ?>
	<div id="content">
		<?php

		$grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($user['email']))) . "?d=" . "&s=148";

		?>
		<div id="usr_img" style="background:url(<?php echo $grav_url; ?>);"></div>
		<strong>Name:</strong> <?php echo($user['name']); ?><br/>
		<strong>Email:</strong> <?php echo($user['email']); ?><br/>
		<strong>Registered:</strong> <?php echo(date_format(date_create($user['date_registered']), 'F j, Y')); ?><br/>
		<strong>Your Computers Specs:</strong> <?php echo($user['rig_specs']); ?>
		<hr/>
		<a href="edit_user.php?id=<?php echo($user['id']); ?>">Edit User</a> | <a href="admin_del_user.php?id=<?php echo($user['id']); ?>">Delete User</a>
	</div>
	<div id="SPACE"></div>
	<?php writeFooter(); ?>
</div>