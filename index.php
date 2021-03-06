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
		<meta charset="utc-8">
		<link rel="stylesheet" href="styles.css">
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="main.js"></script>
		<link rel="icon" type="image/png" href="img/favicon.png">
</head>
<body>

	<div class="sidebar">
		<div class="header">
			<a href="http://victokoh.cs.ucl.ac.uk"><img src="img/main-button.png" class="returnlink"></a>
			<img src="img/parking_title.png" style="display: inline; margin-left: 32px; position: fixed;">
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
						<button id="findnearest">Show on the map</button>
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
			<p class="apitext">Built with the <a href="http://victokoh.cs.ucl.ac.uk/sensors/api.php" class="apitext">Orange Labs Sensors API</a></p>
		</div>
	</div>
	<div id="map">
	</div>

	<script>
		var infowindow;	
		var map = makeMap(document.getElementById('map'));
		var stateType;
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
		var initial = 1;
		var stateType;
		setInterval(function() {
			$.getJSON('getnew.php?initial=' + initial, function(data) {
				$.each(data, function(key, value) {
					if(value.occupied) {
						if(value.legal == 0) {
							stateType = 'illegal';
							if(initial == 1) {
								<?php
									// The message
									$message = "Notice:\r\nYour vehicle is currently illegally parked.\r\n";

									// In case any of our lines are larger than 70 characters, we should use wordwrap()
									$message = wordwrap($message, 70, "\r\n");

									// Send
									mail('gully.johnson@gmail.com', 'Warning from Orange Parking', $message);
								?>
								initial = 0;
							}
						} else {
							stateType = 'occ';
						}
					} else {
						stateType = 'avail';
					}
					console.log(stateType);
					map.updateMarker(value.id, stateType,
						'<p>Id: ' + value.id + '</p>' +
						'<p>Occupied: ' + value.occupied + '</p>' + 
						(value['max-stay'] ? '<p>Max Stay: ' + value['max-stay'] + '</p>' : ''));
				});
				initial = 0;
			});
		}, 3000);
	</script>
</body>
</html>

<?php

	function notifyIllegal() {
		// The message
		$message = "Notice:\r\nYour vehicle is currently illegally parked.\r\n";

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		// Send
		mail('gully.johnson@gmail.com', 'Warning from Orange Parking', $message);
	}
?>