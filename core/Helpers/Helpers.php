<?php

use Core\Iterators\ModelRowIterator;

/**
 * Return user's data
 * @var string
 */
if (!function_exists('user')) {
    function user() {
        return $_SESSION['user'];
    }
}

/**
 * Determine if string is a json
 * @var string
 */
if (!function_exists('isJson')) {
    function isJson($string) {
        if (is_object($string) || is_array($string)) {
            return false;
        }

        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}

/**
 * Pass model to row iterator class
 * Task 6
 *
 * @var object
 */
if (!function_exists('iterate_model')) {
    function iterate_model(object $model)
    {
        return new ModelRowIterator($model);
    }
}

/**
 * CSRF token
 * @var
 */
if (!function_exists('token')) {
    function token() {
        return $_SESSION['token'] ?? '';
    }
}

/**
 * Return asset version
 *
 * @var float
 */
if (!function_exists('asset_version')) {
    function asset_version() {
        return $_ENV['ASSET'];
    }
}

/**
 * Print variable value on file
 *
 * @return void
 */
if (!function_exists('debug_print')) {
    function debug_print($var)
    {
        file_put_contents('debug.log', print_r($var, true));
    }
}

/**
 * Print var values on file and append to existing content
 *
 * @return void
 */
if (!function_exists('debug_print_append')) {
    function debug_print_append($var)
    {
        file_put_contents('debug.log', print_r($var, true)."\n", FILE_APPEND);
    }
}

/**
 * Check if the user is login
 *
 * @return boolean
 */
if (!function_exists('isLogin')) {
    function isLogin()
    {
        if (isset($_SESSION['login'])) { # Issue 84
            return true;
        }

        return false;
    }
}

/**
 * Display the lines traverse by php execution
 *
 * @param boolean $return
 */
if (! function_exists('trace')) {
    function trace($return = false)
    {
        $e = new \Exception();
        $trace = explode("\n", $e->getTraceAsString());

        $trace = array_reverse($trace);
        array_shift($trace);
        array_pop($trace);

        $result = [];

        for ($i = 0; $i < count($trace); $i++) {
            $result[] = ($i + 1)  . '.' . substr($trace[$i], strpos($trace[$i], ' '));
        }

        $result = array_reverse($result);
        $lines  = "\n\t" . implode("\n\t", $result);

        if ($return === true) {
            return $lines;
        }

        var_dump($lines);
    }
}
