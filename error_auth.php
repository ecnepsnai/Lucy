<?php
	require_once("session.php");
	documentCreate(TITLE_ERROR, False); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="red">
		<strong>Authentication Error</strong><br/>
		Uh oh!  You need to be signed in to do that.
	</div>
</div>
<?php writeFooter(); ?>
</div><?php die(); ?>