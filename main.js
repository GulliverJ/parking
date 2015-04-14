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
		addMarker: function(id, lat, lng, colour, content) {
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(lat, lng),
				map: map,
				icon: 'http://maps.google.com/mapfiles/ms/icons/' + colour + '-dot.png',
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
						document.getElementById("restrictions").innerHTML = 'Restrictions between ' + value.rest_start; + ' and ' + value.rest_end;
						document.getElementById("charge").innerHTML = 'Hourly charge: £' + value.nearest_available;
					});
				});
				infoWindow.open(map, marker);
			// Ideally want to initiate code to get the values and display them. right?
			});
			return marker;
		},
		updateMarker: function(id, colour, content) {
			markers[id].setIcon('http://maps.google.com/mapfiles/ms/icons/' + colour + '-dot.png');
			infoWindows[id].setContent(content);
		}

	};

	

}

