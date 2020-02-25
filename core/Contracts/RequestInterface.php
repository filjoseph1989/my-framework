<?php

namespace Core\Contracts;

interface RequestInterface
{
    /**
     * Instantiate request
     */
    public function __construct();

    /**
     * Validate inputs
     *
     * @return void
     */
    public function validate(object $app, object $validator);
}
