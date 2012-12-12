<?php
	require_once("session.php");
	documentCreate(TITLE_ERROR, False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="red">
		<strong>Missing Information</strong><br/>
		Uh oh!  It looks like something was left out on that last page.
	</div>
</div>
<?php writeFooter(); ?>
</div><?php die(); ?>