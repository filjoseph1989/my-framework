<?php

use Core\Iterators\ModelRowIterator;

/**
 * Return first two sentense of a paragraph
 *
 * @param  string $sub_heading
 * @return string
 */
if (!function_exists('excerpt')) {
    function excerpt(object &$draft, int $sentense = 2)
    {
        # Task 60
        // if (isset($_SESSION['excerpt'][$draft->id]) && !empty($_SESSION['excerpt'][$draft->id])) {
        //     return $_SESSION['excerpt'][$draft->id];
        // }

        if (isset($draft->content->sub_heading)) {
            $expression = "/^([^.!?]*[\.!?]+){0,{$sentense}}/";
            preg_match($expression, strip_tags($draft->content->sub_heading), $abstract);
            $_SESSION['excerpt'][$draft->id] = $abstract[0];
            return $abstract[0] ?? '';
        }

        return "";
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
 * Return current mainjs version
 *
 * @var string
 */
if (!function_exists('mainjs_version')) {
    function mainjs_version() {
        return getenv('MAINJS');
    }
}

/**
 * Return asset version
 *
 * @var float
 */
if (!function_exists('asset_version')) {
    function asset_version() {
        return getenv('MAINJS');
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
        file_put_contents('debug.log', print_r($var, true), FILE_APPEND);
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
        if (isset($_SESSION['login'])) {
            return true;
        }

        return false;
    }
}

/**
 * Check if the user is loggedin
 *
 * @return boolean
 */
if (!function_exists('is_login')) {
    function is_login()
    {
        return isLogin();
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
