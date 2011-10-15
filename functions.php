<?php
function db_connect($config)
{
	$db_connection = @mysql_connect ($config['db']['host'], $config['db']['user'], $config['db']['pass']) OR error (mysql_error(),$config, __LINE__, __FILE__, 0, '');
	$db_select = @mysql_select_db ($config['db']['name']) or error (mysql_error(),$config, __LINE__, __FILE__, 0, '');

	return $db_connection;
}

function error($msg,$config, $line='', $file='', $formatted=0,$lang=array())
{
	IF($formatted == 0)
	{
		echo "Low Level Error: " . $msg. " gf:: Line ".$line. " :: File ".$file;
	}
	ELSE
	{
		$page = new HtmlTemplate ("templates/" . $config['tpl_name'] . "/error.html");
		$page->SetParameter ('MESSAGE', $msg);
		$page->CreatePageEcho($lang);
	}
	exit;
}
db_connect($config);

function add($lat, $lng, $description, $photo)
{
	$query = "INSERT INTO even
				( `latitude` , `longitude` , `description`, `photo`) VALUES 
				('".$lat."', ".$lng.", '".$description."', '".$photo."');";
	//echo $query;
	mysql_query($query) OR print_r(mysql_error());
	return mysql_insert_id();
}

function edit($id, $lat, $lng, $description, $photo)
{
	$query = "UPDATE even SET `latitude`= '".$lat."', `longitude` = ".$lng." , `description` = '".$description."', `photo` = '".$photo."' 
			  WHERE id = $id";
	//echo $query;
	mysql_query($query) OR print_r(mysql_error());
	return $id;
}

function del($id)
{
	$query = "DELETE FROM even WHERE id = $id";
	mysql_query($query) OR print_r(mysql_error());
	return true;
}

function get($id)
{
	$results = mysql_query("SELECT * FROM even WHERE id=$id");
	if ($geoloc = mysql_fetch_array($results))
	return $geoloc;
}

function getall()
{
	$query = "SELECT * FROM even ORDER BY id";
	echo $config['db']['table'];
	$results = mysql_query($query) OR print_r(mysql_error());
	if($results)
	{
		while ($geoloc = mysql_fetch_array($results))
		$geolocs[] = $geoloc;
		return $geolocs;
	}
	return false;
}

function format_string($str)
{
	if( !is_numeric($str) )
	{
		$str = nl2br(trim($str));
		$str = str_replace(chr(10),"",$str);
		$str = str_replace(chr(13),"",$str);
	}
	return $str;
}

function setfile($file, $config)
{
	$path = $config['path_file'];
	$path_tmp = $pat. "tmp_" . basename($file['name']);

	if (move_uploaded_file($file['tmp_name'], $path_tmp))
	{
		$path_parts = pathinfo($path_tmp);

		$path_file = $path . filemtime($path_tmp) . '.' . $path_parts['extension'];

		if(rename($path_tmp, $path_file))
		{
			$media_content = '/even/photos/' . filemtime($path_tmp) . '.' . $path_parts['extension'];
			return $media_content;
		}
		else
		{
			$error = "Erreur pendant l'enregistrement de votre fichier";
			return FALSE;
		}
	}
	else
	{
		$error = "Erreur pendant l'enregistrement de votre fichier";
		return FALSE;
	}

}

function format_desc($id=NULL)
{
	if($id!=NULL)
	{
		$geoloc = get($id);
		$desc = addslashes($geoloc['description']).'<br />';
		if($geoloc['photo']!="")
			$desc .= "<img src=".$geoloc['photo']." width=150 height=100><br />";
		//$desc .= "<b>Latitude</b> (dec) : ".$geoloc['latitude']."<br />";
		//$desc .= "<b>Longitude</b> (dec) : ".$geoloc['longitude']."<br />";
		return $desc;
	}
}


/**
 * Resize $source image and save it as $dest file
 *
 * @param string $source
 * @param string $dest
 * @param int $max_width
 * @param int $max_height
 * @param int $quality
 * @return string $dest or false in case of failure
 */
function resizeImage($source, $dest, $max_width, $max_height, $quality = 100)
{
	if ( list($width, $height, $type, $attr) = @getimagesize($source) )
	{
		//if ( $width > 2048 || $height > 2048 ) return false;
			
		// Get new size of picture
		//
		$width_new = ( $width > $max_width ) ? $max_width : $width;
		$height_new = floor(( $height * $width_new ) / $width);
			
		if ( $height_new > $max_height )
		{
			$height_new = $max_height;
			$width_new = floor(( $width * $height_new ) / $height);
		}
			
		// Create the source gd image ressource
		if     ( $type == 1 ) $img_in = imagecreatefromgif($source);
		else if( $type == 2 ) $img_in = imagecreatefromjpeg($source);
		else if( $type == 3 ) $img_in = imagecreatefrompng($source);
		else return false;
			
		if ( $img_in )
		{
			// Create final image
			$img_out = imagecreatetruecolor($width_new, $height_new);
			$bgcolor = imagecolorallocate($img_out, 255, 255, 255);
			imagefill($img_out, 0, 0, $bgcolor);

			// Resize and copy source to final image
			imagecopyresampled($img_out, $img_in, 0, 0, 0, 0, $width_new, $height_new, $width, $height);
			imagedestroy($img_in);

			// Save file
			@unlink($dest);
			imagejpeg($img_out, $dest, $quality);

			imagedestroy($img_out);

			return $dest;
		}
	}
	 
	return false;
}
?>