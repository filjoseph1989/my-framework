<?php

define('START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

$app = new Core\App;

require __DIR__ . '/../route/route.php';

$app->run();
