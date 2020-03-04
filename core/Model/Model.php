<?php

namespace Core\model;

use Core\Model\ObjectMapping;
use Core\Traits\DebugTrait;
use Core\Traits\ModelTrait;

/**
 * Main model
 *
 * @author Fil Beluan
 */
class Model
{
    use ModelTrait;
    use DebugTrait;

    /**
     * The mapping object
     * @var object
     */
    public object $mapper;

    /**
     * Table rows
     * @var array
     */
    public array $rows = [];

    /**
     * Table relationshipe
     * @var array
     */
    public array $relations = [];

    /**
     * Initiate model
     */
    public function __construct()
    {
        $this->mapper = new ObjectMapping($this);
    }

    /**
     * Destruct this model
     */
    public function __destruct() {}

    /**
     * Assign value to the given model property
     *
     * @param string $key   The model property
     * @param mixed  $value The property value
     */
    public function set($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * Remove property from model
     *
     * @param string $prop  The model property
     */
    public function unsetProperty($prop)
    {
        unset($this->$prop);
    }

    /**
     * Return property
     *
     * @param string $property
     * @return void
     */
    private function getProperty(string $property = '')
    {
        return $this->$property;
    }

    /**
     * Magically call mapping object method
     *
     * @param  string $method    Method name
     * @param  array  $arguments The method parameters
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (!method_exists($this->mapper, $method)) {
            return null;
        }

        return $this->mapper->$method($arguments);
    }

    /**
     * Magically call the property
     *
     * @param  mixed $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->getProperty($property);
    }
}
