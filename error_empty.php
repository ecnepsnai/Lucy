<?php
	require("session.php");
	documentCreate(TITLE_ERROR, False, False, null, null); ?>
<div id="wrapper">
<?php writeHeader(); ?>
<div id="content">
	<div class="notice" id="red">
		<strong>Database Error.  It was:</strong><br/>
		Uh oh!  It looks like something was left out on that last page.
	</div>
</div>
<?php writeFooter(); ?>
</div><?php die(); ?>