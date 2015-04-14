<?php
	require 'connect.php';
	$bays = array();
	$query = 'select bays.bay_id, lat, lng, occupied from bays inner join bay_data on bays.bay_id = bay_data.bay_id order by bays.bay_id';
	$result = mysql_query($query);
	if (is_resource($result) && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_row($result)) {
			$bay = array();
			$bay['id'] = $row[0];
			$bay['lat'] = $row[1];
			$bay['lng'] = $row[2];
			$bay['occupied'] = $row[3];
			$bays[] = $bay;
		}
	}
?>
<!DOCTYPE html>

<html>
	<head>
		<link rel="stylesheet" href="styles.css">
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="main.js"></script>
</head>
<body>
	<div class="topbar">
	<a href="http://victokoh.cs.ucl.ac.uk"><img src="img/main-button.png" class="returnlink"></a>
	<div class="header">
		<a href="http://victokoh.cs.ucl.ac.uk/sensors"><img src="img/sensors_logo.png" class="sensorslogo"></a>
		<div class="divide">
		</div>
		<img src="img/parking_logo.png" class="parkinglogo">
		<img src="img/parking_title.png" style="display: inline; float:left; margin-right: 8px">
		<p class="apitext">Built with the <a href="http://students.cs.ucl.ac.uk/2014/group10" class="apitext">Orange Labs Sensors API</a></p>
	</div>
</div>
	<div id="map">
	</div>
	<div class="sidebar">
		<p id="sensorid"></p>
		<p id="occupied"></p>
		<p id="duration"></p>
		<p id="remaining"></p>
		<p id="legal"></p>
		<p id="restricted"></p>
		<p id="max_stay"></p>
		<p id="nearest_unoccupied"></p>
	</div>
	<script>
		var map = makeMap(document.getElementById('map'));		
		<?php
		foreach ($bays as $bay) {
			$colour = ($bay['occupied'] ? 'red' : 'green');
			echo "map.addMarker({$bay['id']}, {$bay['lat']}, {$bay['lng']}, '$colour', '<p>Testing this</p>');";
		} 
		?>
		setInterval(function() {
			$.getJSON('json.php', function(data) {
				$.each(data, function(key, value) {
					map.updateMarker(value.id, (value.occupied ? 'red' : 'green'), '<p>Id: ' + value.id + '</p><p>Occupied ' + value.occupied + '</p>');
				});
			});
		}, 1000);
	</script>
</body>
</html>
