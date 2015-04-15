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
						var getTime = parseTime(value.duration);
						document.getElementById("duration").innerHTML = (getTime.hours ? getTime.hours + ' hours ' : '')
																													+ (getTime.mins ? getTime.mins + ' mins ' : '')
																													+ (getTime.secs ? getTime.secs + ' secs': '');
						getTime = parseTime(value.max_stay);
						document.getElementById("max_stay").innerHTML = (getTime.hours ? getTime.hours + ' hours ' : '')
																													+ (getTime.mins ? getTime.mins + ' mins' : '')
						getTime = parseTime(value.remaining);
						document.getElementById("remaining").innerHTML = (getTime.hours ? getTime.hours + ' hours ' : '')
																													 + (getTime.mins ? getTime.mins + ' mins ' : '')
																												   + (getTime.secs ? getTime.secs + ' secs': '');
						document.getElementById("restricted").innerHTML = value.restricted;
						document.getElementById("legal").innerHTML = value.legal;
						//document.getElementById("nearest_available").innerHTML = value.nearest;
						document.getElementById("restrictions").innerHTML = (value.rstart ? 'Between ' + value.rstart.substring(0, 5) + ' and ' + value.rend.substring(0, 5) : '-');
						document.getElementById("charge").innerHTML = value.charge;
						document.getElementById("findnearest").onclick = function() {
							map.panTo(markers[value.nearest].getPosition());
						};
					});
				});
				document.getElementById("findnearest").onclick = function() {
					map.panTo(markers[value.nearest].getPosition());
				};
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
	var time = {
		hours: hour,
		mins: min,
		secs: sec
	};
	return time;
}
