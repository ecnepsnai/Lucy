<?php
	$con = mysql_connect('localhost','root','');
	if(!$con) {
		require("error_connect.php");
	}
	mysql_select_db('lucy');
?>