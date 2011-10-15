    function createMarker(point, desc) {
      var marker = new GMarker(point);
      GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(desc)});
      return marker;
    }
    
   function findLocation(id, lat, lng, desc, icon) {
	   	if(icon)
	   	{
	    	var userIcon = new GIcon(G_DEFAULT_ICON);
			if(id > 0 && id < 100)
	        	userIcon.image = "http://gmaps-samples.googlecode.com/svn/trunk/markers/"+icon+"/marker"+id+".png";
	        else
	        	userIcon.image = "http://gmaps-samples.googlecode.com/svn/trunk/markers/"+icon+"/blank.png";
			markerOptions = { icon:userIcon };
		}
		else
			markerOptions = "";
		
		var point = new GLatLng(lat, lng);
		var marker = new GMarker(point);
	    map.addOverlay(marker);  
	  	map.panTo(point);
	  	GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(desc)});
	  	if(desc!=undefined) marker.openInfoWindowHtml(desc);
    }