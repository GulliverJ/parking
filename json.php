<?php
	require 'connect.php';
	header('Content-Type: application/json');
	$query = 'select bay_id, occupied from bay_data order by bay_id asc';   
	$result = mysql_query($query);
	echo '[';
	if (is_resource($result) && mysql_num_rows($result)) {
		$initial = true;
		while ($row = mysql_fetch_row($result)) {
			if (!$initial) {
				echo ',';
			}
			$initial = false;
			echo '{';
			echo "\"id\": {$row[0]},";
			echo '"occupied": ' . ($row[1] ? 'true' : 'false'); 
			echo '}';
		}
	}
	echo ']';
?>

