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
				infoWindow.open(map, marker);
			});
			return marker;
		},
		updateMarker: function(id, colour, content) {
			markers[id].setIcon('http://maps.google.com/mapfiles/ms/icons/' + colour + '-dot.png');
			infoWindows[id].setContent(content);
		}
	};
}

