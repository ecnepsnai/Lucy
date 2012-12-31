<?php
	require_once("assets/lib/session.php");
	documentCreate(TITLE_ERROR, False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="red">
		<strong>Connection Error</strong><br/>
		Uh oh!  It looks like we couldn't connect to the database.
	</div>
</div>
<?php writeFooter(); ?>
</div><?php die(); ?>