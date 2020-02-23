<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = new Core\App;

require __DIR__ . '/../route/route.php';

$app->run();
