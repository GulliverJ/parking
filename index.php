<?php
	require 'connect.php';
	$bays = array();
	$query = 'select b.bay_id, lat, lng, v.occupied, v.legally_occupied from bays b inner join bay_data_view v on b.bay_id = v.bay_id order by b.bay_id';
	$result = mysql_query($query);
	if (is_resource($result) && mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_row($result)) {
			$bay = array();
			$bay['id'] = $row[0];
			$bay['lat'] = $row[1];
			$bay['lng'] = $row[2];
			$bay['occupied'] = $row[3];
			$bay['legal'] = $row[4];
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
			<p>Two checkboxes here - filter by 'available' or filter by 'illegal' (todo)</p>
		</div>
		<div class="results">
			<div class="resultshead">
				<div class="resultsbox" style="width: 140px">
					<p.idlabel>Bay ID:</p><br>
					<h4.idlabel id="sensorid"></h4>
				</div>
				<div class="resultsbox" style="width: 220px">
					<p.idlabel>Status:</p><br>
					<h4.idLabel id="occupied"></h4>
				</div>
				<div class="resultsbox" style="width: 40px">
					<p.idlabel>Status:</p><br>
					<h4.idLabel id="occupied"></h4>
				</div>
			</div>
			<div class="resultsmain">
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

			if($bay['occupied']) {
				if($bay['legal']) {
					$type = 'occ';
				} else {
					$type = 'illegal';
				}
			} else {
				$type = 'avail';
			}
			echo "map.addMarker({$bay['id']}, {$bay['lat']}, {$bay['lng']}, '$type', '<p>Loading...</p>');";
		} 
		?>
		setInterval(function() {
			$.getJSON('json.php', function(data) {
				$.each(data, function(key, value) {
					map.updateMarker(value.id, (value.occupied ? 'occ' : 'avail'),
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
