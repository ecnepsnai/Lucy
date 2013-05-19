<?php
	require("../session.php");
	require("../sql.php");
	require("default.php");

	// Administrator access only.
	if(!$usr_Type == "Admin"){
		die("Forbidden.");
	}

	$sql = "SELECT * FROM userlist";
	try {
		$users = sqlQuery($sql, False);
	} catch (Exception $e) {
		die($e);
	}
	getHeader("Users");
	getNav(3);
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
			<td><a href="edit_user.php?id=<?php echo($user['id']); ?>">Edit</a> | <a href="del_user.php?id=<?php echo($user['id']); ?>">Delete</a></td>
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