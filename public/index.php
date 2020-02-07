<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

putenv('DB_HOST=127.0.0.1');
putenv('DB_USERNAME=root');
putenv('DB_PASSWORD=password');
putenv('DB_DATABASE=crateclub2');
putenv('DB_PORT=3306');

$app = new Core\App;

$app->get('/', ['HomeController', 'index']);

$app->run();
