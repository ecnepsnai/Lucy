<?php
	require("session.php");
	documentCreate(TITLE_ERROR, False, False, null, null); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="red">
		<strong>Database Error.  It was:</strong><br/>
		<?php echo(mysql_error()); ?>
	</div>
</div>
<?php writeFooter(); ?>
</div><?php die(); ?>