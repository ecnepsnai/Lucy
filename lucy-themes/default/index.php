<?php
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(),'/lucy-themes/',dirname(__FILE__))));

include('default.php');

getHeader('Welcome'); ?>
<h1>Welcome to Lucy!</h1>
<p>We're here to help in the time of need.  Navigate your way around this site using the links at the top.</p>
<?php getFooter(); ?>