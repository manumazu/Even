<?php
require_once('config.php');
require_once('functions.php');

$point = array('latitude'=>0, 'longitude'=>0);
if( !empty($_GET['id']) && is_numeric($_GET['id']) )
{
	if( $test = get($_GET['id']) )
	$point = $test;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<title>Evénements géolocalisés</title>

	<link rel="stylesheet" type="text/css" media="all" href="styles.css" />
	
	<script
		src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAADGAU7RfnsH8btSZnnCDIXBQF46scg1F69LA-1N1R18FwfmB0KRQ9xOvkaS17kaGrpsjc_SgSmarcfA"
		type="text/javascript"></script>
	<script src="point.js" type="text/javascript"></script>	
	<script type="text/javascript">
	    var map;
	    var address;
	
	    function initialize() {
	      map = new GMap2(document.getElementById("map_canvas"));
	      //init lat and long position and zoom level
	      //map.setCenter(new GLatLng(47.384404,-2.673111), 11);
          map.setCenter(new GLatLng(47.216276000,-1.549151900), 13);
	      map.addControl(new GLargeMapControl);
	      map.addControl(new GMapTypeControl);
		  	      
	      //localize every points
		  <?php if( $list = getall() )
		  foreach($list as $p): ?>
			var point = new GLatLng(<?php echo $p['latitude']; ?>,<?php echo $p['longitude']; ?>);
			desc = "<? echo format_desc($p['id']); ?>";
			map.addOverlay(createMarker(point, desc));
		  <?php endforeach; ?>
	
	      //localize last record in db
		  id = window.location.search.substring(1);
		  if(id!="") {
		  	id = id.split("=");
			desc = "<? echo format_desc($point['id']); ?>";
		    findLocation(id[1], '<?php echo $point['latitude']; ?>', '<?php echo $point['longitude']; ?>', desc, 'orange');    
		  }	  
	    }
	    
	    function del(url) {
	    	test = confirm("Souhaitez vous supprimer cette donnée ?");
	    	if(test) window.location=url;
	    }
	</script>
</head>

<body onload="initialize()" onunload="GUnload()">
<div id="content">
	<h2>Evénements 2009</h2>
	<span class="subtitle">Nantes et son agglomération</span>
	
	<div class="middle">
	<div id="menu"><a href="edit.php">Localiser une nouvel événement</a></div>
	<br/>
	<table>
		<tr>
			<td valign="top">
			<div id="map_canvas" style="width: 800px; height: 700px;"></div>
			</td>
			<td valign="top">
			  <ul>
			  <?php if( $list = getall()):
			  $i = 1;
			  foreach($list as $p): ?>
				<li><a href="javascript:void(0)" 
					onclick="findLocation('<?php echo $p['id']; ?>', '<?php echo $p['latitude']; ?>', '<?php echo $p['longitude']; ?>', '<?php echo format_desc($p['id']); ?>');return false;">
					<?php echo $i; ?>- <? echo stripslashes($p['description']); ?></a> - 
					<a href="/<?php echo $config['dirname'] ?>/edit.php?id=<?php echo $p['id'] ?>" class="edit">Editer</a> -
					<a href="javascript:del('/<?php echo $config['dirname'] ?>/del.php?id=<?php echo $p['id'] ?>');" class="edit">Supprimer</a>
				</li>
			  <?php $i++; endforeach; endif;?>
			  </ul>
			</td>
		</tr>
	</table>
	
	</div>
	
</div>
</body>
</html>
