<?php

$folderPath = dirname($_SERVER['SCRIPT_NAME']);
$urlPath = $_SERVER['REQUEST_URI'];
$url = substr($urlPath,strlen($folderPath));

define('URL', $url);
define('URL_PATH', $folderPath);
define('SITE_ROOT', realpath(dirname(__FILE__)));
define('URL_HOST', $_SERVER['HTTP_HOST']);
