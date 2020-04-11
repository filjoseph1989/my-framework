<?php

namespace Core\Contracts;

interface RequestInterface
{
    /**
     * Instantiate request
     */
    public function __construct();

    /**
     * Verify CSRF Token
     *
     * @return boolean
     */
    public function verifyCsrfToken();

    /**
     * Validate inputs
     *
     * @return void
     */
    public function validate(object $app, object $validator);
}
