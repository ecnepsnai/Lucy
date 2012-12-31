<?php
	require_once("assets/lib/session.php");
	documentCreate(TITLE_ERROR, False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="red">
		<strong>Database Error.  It was:</strong><br/>
		<?php echo($e); ?>
	</div>
</div>
<?php writeFooter(); ?>
</div><?php die(); ?>