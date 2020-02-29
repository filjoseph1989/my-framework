<?php

namespace Core\Request;

use Core\Contracts\RequestInterface;
use Core\Traits\RedirectTrait;

/**
 * Request handler
 *
 * @author Fil Beluan
 */
class Request implements RequestInterface
{
    use RedirectTrait;

    private object $app;

    /**
     * Initiate request instance
     */
    public function __construct()
    {
        # Task 2:
        # Todo 1:
        self::setProperty();
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

                if (!is_null($error)) {
                    $validator->appendError($method, $error);
                }
            }
        }

        if ($validator->hasErrors()) {
            self::redirect()
                ->with(['errors' => $validator->errors()])
                ->to($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Set submitted post data as request property
     *
     * @return void
     */
    private function setProperty()
    {
        foreach ($_POST as $key => $value) {
            unset($_POST[$key]);
            $this->$key = trim($value);
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
