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
						var durationTime = parseTime(value.duration);
						document.getElementById("duration").innerHTML = durationTime.hours + ' hours ' + durationTime.mins + ' mins ' + durationTime.secs + ' secs';
						document.getElementById("max_stay").innerHTML = parseInt(value.max_stay/3600) + " hours, " + parseInt((value.max_stay/60)%60) + " mins";
						document.getElementById("remaining").innerHTML = value.remaining;
						document.getElementById("restricted").innerHTML = value.restricted;
						document.getElementById("legal").innerHTML = value.legal;
						//document.getElementById("nearest_available").innerHTML = value.nearest;
						document.getElementById("restrictions").innerHTML = 'Enforced between ' + value.rstart + ' and ' + value.rend;
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

function parseTime(secs) {
	var tempHour;
	var tempMin;
	var sec = secs % 60;
	var min = parseInt(tempMin / 60) % 60;
	var hour = parseInt(tempHour / 3600);
	var time = {
		hours: hour,
		mins: min,
		secs: sec
	};
	return time;
}
