<?php
require_once('config.php');
require_once('functions.php');
if( !empty($_GET['id']) && is_numeric($_GET['id']) )
{
	del(htmlspecialchars(addslashes($_GET['id'])));
	//redirect
	header('Location: /'.$config['dirname'].'/');
}
?>
