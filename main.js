function makeMap(div) {
	var markers = [];
	var info = [];
	infowindow = new google.maps.InfoWindow();
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
				zIndex: 1,
				icon: 'img/bayicon-' + type + '.png'
			});

			markers[id] = marker;
			info[id] = content;
			google.maps.event.addListener(marker, 'click', function() {
				$.getJSON('dataloader.php?id=' + id, function(data) {
					$.each(data, function(key, value) {
						document.getElementById("sensorid").innerHTML = id;
						document.getElementById("occupied").innerHTML = (value.occupied == 'Occupied') ? value.occupied : '<span style="color: #0b1">' + value.occupied + '</span>';
						document.getElementById("duration").innerHTML = parseInt(value.duration/3600) + " hours, " + parseInt((value.duration/60)%60) + " mins, " + value.duration%60 + " seconds";
						document.getElementById("max_stay").innerHTML = parseInt(value.max_stay/3600) + " hours, " + parseInt((value.max_stay/60)%60) + " mins";
						document.getElementById("remaining").innerHTML = value.remaining;
						document.getElementById("restricted").innerHTML = value.restricted;
						document.getElementById("legal").innerHTML = value.legal;
						document.getElementById("nearest_available").innerHTML = value.nearest_available;
						console.log(value.rest_start);
						document.getElementById("restrictions").innerHTML = 'Enforced between ' + value['rest_start'] + ' and ' + value['rest_end'];
						document.getElementById("charge").innerHTML = value.charge;
						map.panTo(markers[value.nearest_available].getPosition());
					});
				});
				infowindow.setContent(info[id]);
				infowindow.open(map, marker);
			});

			return marker;
		},
		updateMarker: function(id, type, content) {
			markers[id].setIcon('img/bayicon-' + type + '.png');
			var layer = (type == 'occ2') ? 1 : 2;
			markers[id].setZIndex(layer);
			info[id] = content;
		}

	};

	

}

