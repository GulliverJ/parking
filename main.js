function makeMap(div) {
	var markers = [];
	var infoWindows = [];
	var mapOptions = {
		disableDefaultUI: false,
		zoom: 17,
		center: new google.maps.LatLng(51.524498, -0.131812)
	};
	var map = new google.maps.Map(div, mapOptions); 
	return {
		addMarker: function(id, lat, lng, type, content) {
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(lat, lng),
				map: map,
				icon: 'img/bayicon-' + type + '.png',
				title: ''
			});
			var infoWindow = new google.maps.InfoWindow({
				content: content
			});
			markers[id] = marker;
			infoWindows[id] = infoWindow;
			google.maps.event.addListener(marker, 'click', function() {
				$.getJSON('dataloader.php?id=' + id, function(data) {
					$.each(data, function(key, value) {
						console.log(id);
						console.log(value.occupied);
						console.log(value.restricted);
						document.getElementById("sensorid").innerHTML = "ID: " + id;
						document.getElementById("occupied").innerHTML = 'Has been: ' + value.occupied;
						document.getElementById("duration").innerHTML = 'For: ' + value.duration + ' seconds - todo convert';
						document.getElementById("max_stay").innerHTML = 'Time limit: ' + value.max_stay;
						document.getElementById("remaining").innerHTML = 'Time remaining: ' + value.remaining;
						document.getElementById("restricted").innerHTML = value.restricted;
						document.getElementById("legal").innerHTML = 'Legally parked: ' + value.legal;
						document.getElementById("nearest_available").innerHTML = 'Nearest available bay: ' + value.nearest_available;
						document.getElementById("restrictions").innerHTML = 'Restrictions between ' + value.rest_start + ' and ' + value.rest_end;
						document.getElementById("charge").innerHTML = 'Hourly charge: £' + value.charge;
					});
				});
				infoWindow.open(map, marker);
			// Ideally want to initiate code to get the values and display them. right?
			});
			return marker;
		},
		updateMarker: function(id, type, content) {
			markers[id].setIcon('img/bayicon-' + type + '.png');
			infoWindows[id].setContent(content);
		}

	};

	

}

