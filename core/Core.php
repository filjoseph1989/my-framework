<?php
/**
* @package	mynux
* @author fil joseph elman
* @email filjoseph22@gmail.com
* @created October 25, 2015
* @updated February 03, 2020
* @since	Version 1.0.0
* @version 2.0.0
*
* Description
* 	This class set proper reporting values, unregistered globals
* 	and escapes all inputs.
*/
namespace Core;

class Core {
    /**
     * Instantiate
     */
    public function __construct() {
        self::set_reporting();
        self::remove_magic_quotes();
        self::unregister_globals();
    }

    /**
     * Set error reporting
     * Task 21: Review this, if this is really working
     */
    private function set_reporting() {
        if (isset($_SERVER['REMOTE_ADDR']) && ('127.0.0.1' == $_SERVER['REMOTE_ADDR'] || '::1' == $_SERVER['REMOTE_ADDR'])) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
            defined('ERROR_REPORTING') or define('ERROR_REPORTING', 'ON');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
            // ini_set('error_log', ROOT_PATH . DS .'log'); # Task 19 Defined ROOT_PATH and DS contants
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
}
