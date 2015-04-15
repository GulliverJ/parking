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
			<img src="img/parking_title.png" style="display: inline; margin-left: 56px;">
		</div>
		<div class="results">
			<div class="resultshead">
				<div class="resultsbox" style="width: 100px">
					<p class="menulabel">Bay ID: </p>
					<h4 class="menuitem" id="sensorid"></h4>
				</div>
				<div class="resultsbox" style="width: 216px">
					<p class="menulabel">Status:</p>
					<h4 class="menuitem" id="occupied"><i style="font-size: 14px">Click a bay to see its data</i></h4>
				</div>
				<div class="resultsbox" style="width: 60px">
					<img id="icon">
				</div>
			</div>
			<div class="resultsmain">
				<div class="resultsrow">
					<div class="col1">
						<p>Duration</p>
					</div>
					<div class="col2">
						<p id="duration"></p>
					</div>
				</div>
				<div class="resultsrow">
					<div class="col1">
						<p>Maximum Stay</p>
					</div>
					<div class="col2">
						<p id="max_stay"></p>
					</div>
				</div>
				<div class="resultsrow">
					<div class="col1">
						<p>Time Remaining</p>
					</div>
					<div class="col2">
						<p id="remaining"></p>
					</div>
				</div>
				<div class="resultsrow">
					<div class="col1">
						<p>Restricted?</p>
					</div>
					<div class="col2">
						<p id="restricted"></p>
					</div>
				</div>
				<div class="resultsrow">
					<div class="col1">
						<p>Legally Occupied</p>
					</div>
					<div class="col2">
						<p id="legal"></p>
					</div>
				</div>
				<div class="resultsrow">
					<div class="col1">
						<p>Nearest Available Bay</p>
					</div>
					<div class="col2">
						<button id="findnearest" onclick=map.makeMap.addMarker.findNearest()>Show on the map</button>
					</div>
				</div>
				<div class="resultsrow">
					<div class="col1">
						<p>Restrictions</p>
					</div>
					<div class="col2">
						<p id="restrictions"></p>
					</div>
				</div>
				<div class="resultsrow">
					<div class="col1">
						<p>Hourly Charge</p>
					</div>
					<div class="col2">
						<p id="charge"></p>
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<p class="apitext">Built with the <a href="http://students.cs.ucl.ac.uk/2014/group10" class="apitext">Orange Labs Sensors API</a></p>
		</div>
	</div>
	<div id="map">
	</div>

	<script>
		var infowindow;	
		var map = makeMap(document.getElementById('map'));
		<?php
		foreach ($bays as $bay) {

			if($bay['occupied']) {
				if($bay['legal']) {
					$type = 'occ2';
				} else {
					$type = 'illegal';
				}
			} else {
				$type = 'avail2';
			}
			echo "map.addMarker({$bay['id']}, {$bay['lat']}, {$bay['lng']}, '$type', '<p>Loading...</p>');";
		} 
		?>
		setInterval(function() {
			$.getJSON('json.php', function(data) {
				$.each(data, function(key, value) {
					map.updateMarker(value.id, (value.occupied ? 'occ2' : 'avail2'),
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
