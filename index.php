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

	<div class="sidebar">
		<div class="header">
			<a href="http://victokoh.cs.ucl.ac.uk"><img src="img/main-button.png" class="returnlink"></a>
			<img src="img/parking_title.png" style="display: inline; margin-left: 88px;">
		</div>
		<div class="searchdiv">
			<input name="search" type="text" placeholder="To be replaced by Google API" class="mainsearch"></input>
		</div>
		<div class="results">
			<p>Side note if you're passing through and curious - this section is essentially a more qualitative description of the sensor's data. Having a tile pop up on the map to show the basic is great (definitely worth keeping - this menu panel may be hidden on phones and replaced with just the map), but I worry it might be unreasonable to show every piece of information on it - plus it'd be easier to demonstrate the project to people this way. The aesthetics of this box are the last bit I need to do - have run out to finish my passport stuff and get some food beforehand.
			<p id="sensorid"></p>
			<p id="occupied"></p>
			<p id="duration"></p>
			<p id="max_stay"></p>
			<p id="remaining"></p>
			<p id="restricted"></p>
			<p id="legal"></p>
			<p id="nearest_available"></p>
			<p id="restrictions"></p>
			<p id="charge"></p>
		</div>
		<div class="footer">
			<p class="apitext">Built with the <a href="http://students.cs.ucl.ac.uk/2014/group10" class="apitext">Orange Labs Sensors API</a></p>
		</div>
	</div>
	<div id="map">
	</div>

	<script>
		var map = makeMap(document.getElementById('map'));		
		<?php
		foreach ($bays as $bay) {
			$colour = ($bay['occupied'] ? 'red' : 'green');
			echo "map.addMarker({$bay['id']}, {$bay['lat']}, {$bay['lng']}, '$colour', '<p>Loading...</p>');";
		} 
		?>
		setInterval(function() {
			$.getJSON('json.php', function(data) {
				$.each(data, function(key, value) {
					map.updateMarker(value.id, (value.occupied ? 'red' : 'green'),
						'<p>Id: ' + value.id + '</p>' +
						'<p>Occupied: ' + value.occupied + '</p>' + 
						'<p>Nearest Available Bay:' + value['nearest-unoccupied-bay'] + '</p>' + 
						(value['max-stay'] ? '<p>Max Stay: ' + value['max-stay'] + '</p>' : '') + 
						'<p>Start ' + value.start + '</p>' + 
						'<p>End ' + value.end + '</p>');
				});
			});
		}, 1000);
	</script>
</body>
</html>
