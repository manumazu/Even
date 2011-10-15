<?php
global $config;

$vhost = $_SERVER["HTTP_HOST"];
switch($vhost)
{
	//DEV
	case 'localhost':
		//db conf
		$config['db']['host'] = '';
		$config['db']['name'] = '';
		$config['db']['user'] = '';
		$config['db']['pass'] = '';
		$config['db']['table'] = '';	
		//url base with 'http://'	
		$config['site_base'] = '';
		//project dir name
		$config['dirname'] = '';
		//physical path to project 'photos' dir
		$config['path_file'] = '';
		break;
}
?>
