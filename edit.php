<?php
require_once('config.php');
require_once('functions.php');

$point = array('latitude'=>0, 'longitude'=>0);
if( !empty($_GET['id']) && is_numeric($_GET['id']) )
{
	if( $test = get($_GET['id']) )
	$point = $test;
}

if( isset($_POST['sendform']) )
{
	//format strings
	$photo = "";
	if( isset($_FILES['photo']) && count($_FILES)>0 )
		$photo = setfile($_FILES['photo'], $config);

	$func = create_function('&$v', '$v = format_string($v);');
	array_walk_recursive(&$_POST, $func);
	
	$description = $_POST['description'];
	if($_POST['address']!="")
	{
		$description = 	"<b>".$_POST['address']."</b><br />".$description;
	}
	
	//save datas
	if( !empty($_GET['id']) && is_numeric($_GET['id']) )
	{
		//echo $photo;
		if($photo=="") $photo = $point['photo'];
		$lastid = edit($_GET['id'], $_POST['latitude'], $_POST['longitude'], $description, $photo);
	}
	else
	{
		$lastid = add($_POST['latitude'],$_POST['longitude'], $description, $photo);
	}

	//redirect
	header('Location: /'.$config['dirname'].'/edit.php?id='.$lastid);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Edition des événements à géolocaliser</title>

<link rel="stylesheet" type="text/css" media="all" href="styles.css" />

<script
	src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAADGAU7RfnsH8btSZnnCDIXBQF46scg1F69LA-1N1R18FwfmB0KRQ9xOvkaS17kaGrpsjc_SgSmarcfA"
	type="text/javascript"></script>
	
<script type="text/javascript">
    var map;
    var address;

    function initialize() {
      map = new GMap2(document.getElementById("map_canvas"));
      //map.setCenter(new GLatLng(47.384404,-2.673111), 13);
      map.setCenter(new GLatLng(47.216276000,-1.549151900), 13);
      map.addControl(new GLargeMapControl);
      map.addControl(new GMapTypeControl);
      
      geocoder = new GClientGeocoder();

      //map.setMapType(G_SATELLITE_MAP);
      GEvent.addListener(map, "click", getPoint);
      
      //localize every points
	  <?php if( $list = getall() )
	  foreach($list as $p): ?>
		var point = new GLatLng(<?php echo $p['latitude']; ?>,<?php echo $p['longitude']; ?>);
		desc = "<? echo format_desc($p['id']); ?>";
		map.addOverlay(createMarker(point, desc));
	  <?php endforeach; ?>
	  
	  
		id = window.location.search.substring(1);
		if(id!="") {
			var p = new GLatLng(<?php echo $point['latitude']; ?>,<?php echo $point['longitude']; ?>);
			editPoint(p); 
		}
    }
</script>
<script src="point.js" type="text/javascript"></script>
<script src="edit.js" type="text/javascript"></script>
</head>

<body onload="initialize()" onunload="GUnload()">
<div id="content">
	<h2>Evénements 2009</h2>
	<span class="subtitle">Nantes et son agglomération</span>
	
	<div class="middle">
	<div id="menu"><a href="<?php echo "/".$config['dirname']."/" ?>">Liste des événements</a></div>
	<br />
		<form method="post" name="findll" enctype="multipart/form-data" onsubmit="editMap(this); return false">
		<table>
			<tr>
				<td valign="top">
				<div id="map_canvas" style="width:700px;height:600px;"></div>
				</td>
				<td valign="top">
				<h3>Adresse</h3>
				<b>Adresse :</b> <input type="text" size="60" name="address" value="" /><br />
				<h3>Position géographique</h3>
				
				<b>Latitude :</b> <input type="text" size="2" name="lat1" value="" />°
				<input type="text" size="2" name="lat2" value="" />' 
				<input type="text" size="4" name="lat3" value="" onchange="javascript:this.value=this.value.replace( ',' , '.' )" />''
				&nbsp; <input type="radio" name="latd" value="N" checked="checked" />
				Nord <input type="radio" name="latd" value="S" /> Sud <br />
				
				<b>Longitude :</b> <input type="text" size="2" name="lng1" value="" />°
				<input type="text" size="2" name="lng2" value="" />' <input
					type="text" size="4" name="lng3" value=""
					onchange="javascript:this.value=this.value.replace( ',' , '.' )" />''
				&nbsp; <input type="radio" name="lngd" value="W" checked="checked" />
				Ouest <input type="radio" name="lngd" value="E" /> Est
		
				<h3>Information complémentaire</h3>
				<textarea rows="5" cols="25" name="description"><? echo stripslashes($point['description']); ?></textarea>
				<h3>Photo</h3>
				<?php if($point['photo']!="")?><img src="<?php echo $point['photo']; ?>">
				<input type="file" name="photo" />
				<div id="message"></div>
				<br />
				<input type="hidden" name="latitude" /> <input type="hidden"
					name="longitude" /> <input type="hidden" name="sendform" /> 
				<input type="submit" value="Prévisualiser" />
				&nbsp;&nbsp;
				<input type="reset" value="Réinitialiser" />
				&nbsp;&nbsp;
				<input type="button" value="Enregistrer" onclick="javascript:validform();" />
				</td>
			</tr>
		</table>
		
		</form>
	</div>
	
</div>
</body>
</html>
