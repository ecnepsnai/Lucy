<?php
	require("session.php");

	// This page requires a user to be signed in.
	if(!$usr_IsSignedIn){
		require("error_auth.php");
	}

	require("sql.php");

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

	documentCreate("Your Profile", False); ?>
	<div id="wrapper">
		<?php writeHeader(); ?>
	<div id="content">
		<?php

		$grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($user['email']))) . "?d=" . "&s=200";

		?>
		<img src="<?php echo $grav_url; ?>" alt="Cover photo." class="profileimg"/>
			<strong>Name:</strong> <?php echo($user['name']); ?><br/>
			<strong>Email:</strong> <?php echo($user['email']); ?><br/>
			<strong>Registered:</strong> <?php echo(date_format(date_create($user['date_created']), 'F j, Y')); ?>
					<hr/>
					<a href="admin/edit_user.php?id=<?php echo($user['id']); ?>">Edit User</a> | <a href="admin/del_user.php?id=<?php echo($user['id']); ?>">Delete User</a>
	</div>
	<?php writeFooter(); ?>
</div>