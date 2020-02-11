<?php

namespace Core\Request;

/**
 * Request handler
 *
 * @author Fil Beluan
 */
class Request
{
    /**
     * Container of post submitted data
     * @var array
     */
    private array $postData = [];

    /**
     * Initiate request instance
     */
    public function __construct() { }

    /**
     * Validate request input
     *
     * @return void
     */
    public function validate()
    {
        # Task 2:
        # Todo 1:
        # validate, trim, sanitize and scape mysql string

        $this->postData = $_POST;

        self::setProperty();
    }

    /**
     * Set submitted post data as request property
     *
     * @return void
     */
    private function setProperty()
    {
        foreach ($this->postData as $key => $value) {
            $this->$key = $value;
        }
    }
}
