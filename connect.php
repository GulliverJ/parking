<?php
$server = 'localhost';
$user = 'orangeadmin';
$password = 'orangejuice';
$database = 'orangeparking';

mysql_connect($server, $user, $password) or die('Could not connect');
mysql_select_db($database) or die ('No database'); 

?>
