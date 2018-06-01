<?php
use utils\Application;

require './vendor/autoload.php';

require './config/bootstrap.php';

error_reporting(E_ALL ^ E_NOTICE);

spl_autoload_register(function($class){
    $class = strtr($class, '\\', '/');
    require_once __DIR__.'/'.$class.'.php';
});

$app = new Application();
$app->run();
