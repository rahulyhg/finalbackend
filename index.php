<?php

require_once './include/DbHandler.php';
require_once './include/PassHash.php';
require '././libs/Slim/Slim.php';
require './vendor/autoload.php';
require './include/simple_html_dom.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$path1 = dirname(__DIR__);
define('pic_image',$path1."/v1/uploads");
ini_set('display_errors', 'On');
error_reporting(E_ALL);
// User id from db - Global Variable
$user_id = NULL;
phpinfo();
$app->run();
?>