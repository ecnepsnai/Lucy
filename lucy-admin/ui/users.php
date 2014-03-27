<?php
	require("../session.php");
	require("../cda.php");
	require("default.php");

	// Administrator access only
	if($usr_Type != "Admin"){
		lucy_die(0);
	}

	// Creating the CDA class.
	$cda = new cda;
	// Initializing the CDA class.
	$cda->init($GLOBALS['config']['Database']['Type']);

	try {
		$response = $cda->select(array("name","email","type","id"),"userlist",null);
	} catch (Exception $e) {
		die($e);
	}
	$users = $response['data'];

	// Correcting issue if there is only one item in the database.
	if(isset($users['name'])){
		$users = array($users);
	}

	getHeader("Users");
	getNav(4);

	if(isset($_GET['notice'])){
		switch ($_GET['notice']) {
			case 'create': ?>
				<div class="alert alert-success"><strong>User Created</strong></div>
			<?php break;
			case 'del': ?>
				<div class="alert alert-warning"><strong>User Deleted</strong></div>
			<?php break;
		}
	}
?>
<h1>All Users</h1>
<table class="table table-hover">
	<thead>
		<tr>
			<th><strong>Name</strong></th>
			<th><strong>Email</strong></th>
			<th><strong>Type</strong></th>
			<th><strong>Actions</strong></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($users as $user) { ?>
		<tr>
			
			<td><?php echo($user['name']); ?></td>
			<td><?php echo($user['email']); ?></td>
			<td><?php echo($user['type']); ?></td>
			<?php if($user['type'] == "Bot"){ echo('<td>...</td>'); } else { ?>
			<td><a href="edit_user.php?id=<?php echo($user['id']); ?>">Edit</a> | <a href="del_user.php?id=<?php echo($user['id']); ?>">Delete</a></td>
			<?php } ?>
		</tr>
	<?php } ?>
</tbody>
</table>
			<div id="buttons">
				<form action="new_user.php" method="post">
					<input type="submit" value="Create New User" class="btn btn-primary"/>
				</form>
			</div>
<?php getFooter(); ?>