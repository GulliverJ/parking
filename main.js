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

				console.log(id);
				$(function() {
					console.log(id);
					$.getJSON('dataloader.php', function(data) {
						console.log(id);
						$.each(data, function(key, value) {
							console.log(data);
							console.log(value);

							document.getElementByID("occupied").innerHTML = 'Occupied: ' + value.occupied;
							document.getElementByID("duration").innerHTML = 'For: ' + value.duration;
							document.getElementByID("remaining").innerHTML = 'Time remaining: ' + value.remaining;
							document.getElementByID("legal").innerHTML = 'Legally parked: ' + value.legal;
							document.getElementByID("restricted").innerHTML = 'Restricted: ' + value.restricted;
							document.getElementByID("max_stay").innerHTML = 'Maximum stay: ' + value.max_stay;
							document.getElementByID("nearest_unoccupied").innerHTML = 'Nearest unoccupied bay: ' + value.nearest_unoccupied;

						});
					});
				});
				infoWindow.open(map, marker);
				// Ideally want to initiate code to get the values and display them. right?
			});
			return marker;
		},
		updateMarker: function(id, colour, content) {
			//markers[id].setIcon('http://maps.google.com/mapfiles/ms/icons/' + colour + '-dot.png');
			markers[id].setIcon('img/testicon.png');
			infoWindows[id].setContent(content);
		}

	};

	

}

