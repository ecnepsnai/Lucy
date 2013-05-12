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
	getSidebar(3);
?>
		<div id="content">
			<h2>All Users</h2>
			<table>
				<tr>
					<td><strong>Name</strong></td>
					<td><strong>Email</strong></td>
					<td><strong>Type</strong></td>
					<td><strong>Actions</strong></td>
				</tr>
			<?php foreach ($users as $user) { ?>
				<tr>
					<td><?php echo($user['name']); ?></td>
					<td><?php echo($user['email']); ?></td>
					<td><?php echo($user['type']); ?></td>
					<td><a href="edit_user.php?id=<?php echo($user['id']); ?>">Edit</a> | <a href="del_user.php?id=<?php echo($user['id']); ?>">Delete</a></td>
				</tr>
			<?php } ?>
			</table>
			<div id="buttons">
				<form action="new_user.php" method="post">
					<input type="submit" value="Create New User" />
				</form>
			</div>
		</div>
	</div>
	<?php getFooter(); ?>
</div>