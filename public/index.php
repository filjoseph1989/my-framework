<?php

/**
 * Please note that this application require PHP 8 or above
 * 
 * @author Fil Joseph Elman <filjoseph22@gmail.com>
 */
if (version_compare(phpversion(), '8.0.0', "<")) {
    echo 'The application required PHP version 8.0.0 or above: ' . phpversion() . "\n";
    exit;
}

define('START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';

use Core\App;

$app = new App;

require __DIR__ . '/../route/route.php';

$app->run();