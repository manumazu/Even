	function editMap(form) {

		lat1 = form.lat1.value; lat2 = form.lat2.value; lat3 = form.lat3.value;
		lat = lat1 + lat2 + lat3;
		
		lng1 = form.lng1.value; lng2 = form.lng2.value; lng3 = form.lng3.value
		lng = lng1 + lng2 + lng3;
		
		if(form.address.value != "" && (lat == "" && lng == "")) {
			findaddress(form.address.value);
		}
		
		else if(lat != "" && lng != ""){
			lat = dms2dec(lat1, lat2 , lat3 , form.latd[1].checked);
			lng = dms2dec(lng1, lng2 , lng3 , form.lngd[0].checked);
			editPoint(lat, lng);
		}
	}
	
	function displaycomment() {
		msg = '';
		add = document.findll.address.value;
		if(add!="") msg = msg + add + '<br />';
		msg = msg + document.findll.description.value
		return msg;
	}

    function getPoint(overlay, latlng) {
      if (latlng != null) {
		editPoint(latlng);
      }
    }
    
	function findaddress(address)
	{
		if (geocoder) 
		{
			geocoder.getLatLng(address,
			  function(point) {
			    if (!point) {
			      alert(address + " non trouv\u00E9");
			    } else {
			      map.setCenter(point, 11);
			      //var marker = new GMarker(point);
			      //map.addOverlay(marker);
			      //marker.openInfoWindowHtml(address);
			      editPoint(point);
			    }
			  }
			);
		}
	}    

    function editPoint(latlng, longitude) {
        map.clearOverlays();
		coord='';
		
		//set position with form
		if(longitude != undefined) {
			latitude = latlng;
		}
	
		//set position with onclick : fill form automaticaly
		else {
			latitude = latlng.lat();
			if(latitude < 0) {
				lat = -latitude;
				document.findll.latd[1].checked=true;
			}
			else {
				lat = latitude;
				document.findll.latd[0].checked=true;
			}		
			lat = dec2dms(lat);
			
			longitude = latlng.lng();
			if(longitude < 0) {
				lng = -longitude;
				document.findll.lngd[0].checked=true;
			}
			else {
				lng = longitude;
				document.findll.lngd[1].checked=true;
			}
			lng = dec2dms(lng);

			document.findll.lat1.value=lat[0];
			document.findll.lat2.value=lat[1];
			document.findll.lat3.value=lat[2];
			
			document.findll.lng1.value=lng[0];
			document.findll.lng2.value=lng[1];
			document.findll.lng3.value=lng[2];
		}

		document.findll.latitude.value=latitude;
		document.findll.longitude.value=longitude;	

		point = new GLatLng(latitude, longitude);			
	    marker = new GMarker(point, {draggable: true});
	    map.addOverlay(marker);
	  	map.panTo(point);
	  	//coord = '<b>pos pixel: </b>' + map.fromLatLngToDivPixel(latlng) + '<br>' + '<b>niveau de zoom: </b> ' + map.getZoom();
	    document.getElementById("message").innerHTML = coord;
	    msg = displaycomment();
		marker.openInfoWindowHtml(msg);
    }
    
	function dms2dec(d,m,s,neg) {
		if( d=="" || m=="" ) {
			alert("Les coordonn\u00E9es ne sont pas au bon format");
			return false;
		}
		res = parseInt(d) + (parseFloat(m) + parseFloat(s/60))/60;
		if(neg) res = -res;
		return res;
	}
	
	function dec2dms(l) {
		d = Math.floor(l);
		p = (l - d) * 60;
		m = Math.floor(p);
		s = Math.round(((p - m) * 60)*100)/100;
		return new Array(d,m,s);
	}
	
	function validform() {
		with(document.findll) {
			if( lat1.value=="" || lat2.value=="" || lng1.value=="" || lng2.value=="" ) {
				alert("Les coordonn\u00E9es ne sont pas au bon format");
				return false;
			}
			else if( description.value=="" ) {
				alert("Merci de saisir un compl\u00E9ment d'information");
				return false;			
			}
			else {
				document.findll.submit();
			}
			return false;
		}
	}

