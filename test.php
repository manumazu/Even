<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Maps JavaScript API Example</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAADGAU7RfnsH8btSZnnCDIXBQF46scg1F69LA-1N1R18FwfmB0KRQ9xOvkaS17kaGrpsjc_SgSmarcfA"
      type="text/javascript"></script>

    <script type="text/javascript">
    var map;
    var geocoder;
    var address;

    function initialize() {
      map = new GMap2(document.getElementById("map_canvas"));
      map.setCenter(new GLatLng(47.384404,-2.673111), 11);
      map.addControl(new GLargeMapControl);
      //map.setMapType(G_SATELLITE_MAP);
      GEvent.addListener(map, "click", getAddress);
    }
    
    function getAddress(overlay, latlng) {
      if (latlng != null) {
	showAddress(latlng);
      }
    }

    function showAddress(latlng, longitude) {
        map.clearOverlays();

	if(longitude != undefined) {
		point = new GLatLng(latlng, longitude);
		msg = '<h3>Infos de position</h3>'+
		'<b>latitude: </b>' + latlng + '<br>' +
		'<b>longitude: </b>' + longitude + '<br>';	
	}
	else {
        	point = new GLatLng(latlng.lat(), latlng.lng());

		msg = '<h3>Infos de position</h3>'+
		'<b>latitude: </b>' + latlng.lat() + '<br>' +
		'<b>longitude: </b>' + latlng.lng() + '<br>' +
		'<b>pos pixel: </b>' + map.fromLatLngToDivPixel(latlng) + '<br>' + 
		'<b>niveau de zoom: </b> ' + map.getZoom();

		document.findll.latitude.value=latlng.lat();
		document.findll.longitude.value=latlng.lng();
	}

        marker = new GMarker(point, {draggable: true});
        map.addOverlay(marker);
	document.getElementById("message").innerHTML = msg;
			marker.openInfoWindowHtml(msg);
    }

    </script>

<!--

    function load() 
    {
        var map = new GMap2(document.getElementById("map"));
        map.setCenter(new GLatLng(47.29425,-2.510204), 14);

        GEvent.addListener(map, "click", getAddress);
        geocoder = new GClientGeocoder();
    }
        
        //map.addControl(new GSmallMapControl());
  	//map.setMapType(G_SATELLITE_MAP);


	 GEvent.addListener(map, "click", function(overlay,latlng) {
          var lat = latlng.lat();
          var lon = latlng.lng();
	  var marker = new GMarker(new GLatLng(lat, lon), {draggable: true});
	  map.addOverlay(marker);

        });
-->
  </head>
  <body onload="initialize()" onunload="GUnload()">

   <h1> Test de carte interactive </h1>

    <form name='findll' action="#" onsubmit="showAddress(this.latitude.value,this.longitude.value); return false">
      <p>
        latitude <input type="text" size="15" name="latitude" value="" />
	longitude <input type="text" size="15" name="longitude" value="" />
        <input type="submit" value="Go!" />
      </p>
    </form>

<table>
<tr>
<td valign="top">
   <div id="map_canvas" style="width:700px;height:600px;float:left;""></div>
</td>
<td valign="top">
   <div id="message" style="float:right;"></div>
</td>
</tr>
</table>


<!---iframe width="750" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://local.google.fr/maps/ms?ie=UTF8&amp;hl=fr&amp;t=p&amp;s=AARTsJrmoh7SRfp0C6CWbT_To6gcgU7CDQ&amp;msa=0&amp;msid=113681592930337484008.00046229e06fcddb03989&amp;ll=47.402067,-2.681351&amp;spn=0.278853,0.514984&amp;z=11&amp;output=embed"></iframe><br /><small><a href="http://local.google.fr/maps/ms?ie=UTF8&amp;hl=fr&amp;t=p&amp;msa=0&amp;msid=113681592930337484008.00046229e06fcddb03989&amp;ll=47.402067,-2.681351&amp;spn=0.278853,0.514984&amp;z=11&amp;source=embed" style="color:#0000FF;text-align:left">Agrandir le plan</a></small--->

  </body>
</html>
