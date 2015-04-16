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
				animation: null,
				icon: 'img/bayicon-' + type + '.png'
			});

			markers[id] = marker;
			info[id] = content;
			google.maps.event.addListener(marker, 'click', function() {
				$.getJSON('dataloader.php?id=' + id, function(data) {
					$.each(data, function(key, value) {
						document.getElementById("sensorid").innerHTML = id;
						document.getElementById("occupied").innerHTML = (value.occupied == 'Occupied') ? value.occupied : '<span style="color: #0b1">' + value.occupied + '</span>';
						document.getElementById("duration").innerHTML = parseTime(value.duration);
						document.getElementById("max_stay").innerHTML = (parseTime(value.max_stay) ? parseTime(value.max_stay) : '-');
						document.getElementById("remaining").innerHTML = (parseTime(value.remaining) ? parseTime(value.remaining) : '-');
						document.getElementById("restricted").innerHTML = value.restricted;
						document.getElementById("legal").innerHTML = value.legal == 'NULL' || value.occupied = 'Available' ? '-' : value.legal == 1 ? 'Yes' : 'No';
						document.getElementById("restrictions").innerHTML = (value.rstart ? 'Between ' + value.rstart.substring(0, 5) + ' and ' + value.rend.substring(0, 5) : '-');
						document.getElementById("charge").innerHTML = value.charge;
						document.getElementById("findnearest").onclick = function() {
							map.panTo(markers[value.nearest].getPosition());
							markers[value.nearest].setAnimation(google.maps.Animation.BOUNCE);
							setTimeout(function(){ 
								markers[value.nearest].setAnimation(null); 
							}, 1400);
						};
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
	var sec = secs % 60;
	var min = parseInt(secs / 60) % 60;
	var hour = parseInt(secs / 3600);
	var result = '';
	var none = 0;
	if(hour > 0) {
		result += hour + (hour == 1 ? ' hour ' : ' hours ');
	} else { none++; }
	if(min > 0) {
		result += min + (min == 1 ? ' min ' : ' mins ');
	} else { none++; }
	if(sec > 0) {
		result += sec + (sec == 1 ? ' sec ' : ' secs ');
	} else { none++; }
	return (none > 0 ? result : '');
}
