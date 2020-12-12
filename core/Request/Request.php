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
     * Container of the uploaded files
     * @var array
     */
    private array $preservedFiles = [];

    /**
     * Container for counts
     * @var int
     */
    private int $preservedFilesCount;
    private int $preservedInputsCount;

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
     * Here we accept an object $validator
     * and use $validator to evaluate every input gathered 
     * by the request
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
            if ($this->want_json == 'true') {
                return ['errors' => $validator->errors()];
            } else {
                self::redirect()
                    ->with(['errors' => $validator->errors()])
                    ->inputs($this->preservedInputs)
                    ->to($_SERVER['HTTP_REFERER']);
            }
        }

        return true;
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
     * Return the inputs count
     * @return int
     */
    public function getPreservedInputsCount()
    {
        return $this->preservedInputsCount;
    }

    /**
     * Return array of uploaded files
     *
     * @return array
     */
    public function getPreservedFiles()
    {
        return $this->preservedFiles;
    }

    /**
     * Return the uploaded files count
     * @return int
     */
    public function getPreservedFilesCount()
    {
        return $this->preservedFilesCount;
    }

    /**
     * Set submitted post data as request property
     *
     * @return void
     */
    private function setProperty()
    {
        $this->preservedInputs      = $_POST;
        $this->preservedFiles       = $_FILES;
        $this->preservedInputsCount = is_countable($_POST) ? count($_POST) : 0;
        $this->preservedFilesCount  = is_countable($_FILES) ? count($_FILES) : 0;

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
