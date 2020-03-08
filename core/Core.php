<?php

namespace Core;

use Dotenv;

/**
 * 	This class set proper reporting values, unregistered globals
 * 	and escapes all inputs.
 *
 * @created October 25, 2015
 * @updated February 03, 2020
 * @author Fil Beluan <filjoseph22@gmail.com>
 * @since	Version 1.0.0
 * @version 2.0.0
 */
class Core {
    /**
     * Instantiate
     */
    public function __construct() {
        self::setEnv();
        self::errorHandling();
        self::set_reporting();  # Issue 55
        self::remove_magic_quotes();
        self::unregister_globals();
    }

    /**
     * Register whoops error handling
     *
     * @return void
     */
    public function errorHandling()
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);

        if (getenv('DEBUG') === 'true') {
            $whoops->register();
        } else {
            $whoops->unregister();
        }
    }

    /**
     * Set error reporting
     * Issue 34
     *
     * @return void
     */
    private function set_reporting() {
        if (getenv('DEBUG') === 'true') {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
            defined('ERROR_REPORTING') or define('ERROR_REPORTING', 'ON');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
            ini_set('error_log', 'error.log'); // Issue 35
            defined('ERROR_REPORTING') or define('ERROR_REPORTING', 'OFF');
        }
    }

    /**
     * Cleanup
     *
     * @param  mixed $value
     * @return mixed
     */
    private function strip_slashes_deep($value) {
        return is_array($value)
            ? array_map([$this, 'strip_slashes_deep'], $value)
            : stripslashes($value);
    }

    /**
     * Remove magic quote
     *
     * @return void
     */
    private function remove_magic_quotes() {
        $_GET    = $this->strip_slashes_deep($_GET);
        $_POST   = $this->strip_slashes_deep($_POST);
        $_COOKIE = $this->strip_slashes_deep($_COOKIE);
    }

    /**
     * Unregister globals
     *
     * @return void
     */
    private function unregister_globals() {
        if (ini_get('register_globals')) {
            $array = ['_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES'];

            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    /**
     * Load env
     */
    private function setEnv()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    }
}
