<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

Use eftec\bladeone\BladeOne;

$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';

$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

echo $blade->run("index", array("name" => "Fil Joseph"));
