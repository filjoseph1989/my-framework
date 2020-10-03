<?php

namespace Core\Request;

use Core\Contracts\RequestInterface;
use Core\Traits\RedirectTrait;
use Core\Files;

/**
 * Request handler
 *
 * @author Fil Beluan
 */
class Request implements RequestInterface
{
    use RedirectTrait;

    /**
     * Container of submitted input using POST
     * @var array
     */
    private array $preservedInputs = [];

    /**
     * App instance container
     * @var object
     */
    private object $app;

    /**
     * Initiate request instance
     */
    public function __construct()
    {
        self::setProperty();
    }

    /**
     * Verify CSRF Token
     *
     * @return boolean
     */
    public function verifyCsrfToken()
    {
        if (isset($this->token)) {
            if (hash_equals($_SESSION['token'], $this->token)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate request inputs
     *
     * @return void
     */
    public function validate(object $app, object $validator)
    {
        $this->app = $app;

        foreach ($this as $method => $input) {
            if (method_exists($validator, $method)) {
                $error = $validator->$method($input);
            }

            if ($validator->magicallyCall()) {
                $error = $validator->$method($input);
            }

            if (isset($error) && !is_null($error)) {
                $validator->appendError($method, $error);
                unset($error);
            }
        }

        if ($validator->hasErrors()) {
            self::redirect()
                ->with(['errors' => $validator->errors()])
                ->inputs($this->preservedInputs)
                ->to($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Return array of form inputs
     *
     * @return array
     */
    public function getPreservedInputs()
    {
        return $this->preservedInputs;
    }

    /**
     * Set submitted post data as request property
     *
     * @return void
     */
    private function setProperty()
    {
        $this->preservedInputs = $_POST;
        $this->preservedFiles  = $_FILES;

        self::loopPost();
        self::loopFile();
    }

    /**
     * Evaluate every submitted data
     *
     * @return void
     */
    private function loopPost()
    {
        foreach ($_POST as $key => $value) {
            unset($_POST[$key]);

            if ($key !== "password" && $key !== 'token') {
                $key = str_replace('-', '_', $key);
            }

            $this->$key = trim($value, " \t\n\r\0\x0B");
        }
    }

    /**
     * evaluate every submitted files
     *
     * @return void
     */
    private function loopFile()
    {
        foreach ($_FILES as $key => $value) {
            unset($_FILES[$key]);
            $this->$key = new Files($value);
        }
    }

    /**
     * Return the app instance
     *
     * @return object
     */
    private function app()
    {
        return $this->app;
    }
}
