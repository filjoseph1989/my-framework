<?php

namespace Core\Model;

use Core\Mapper\ObjectMapper;
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
    protected object $mapper;

    /**
     * Table rows
     * @var array
     */
    protected array $rows = [];

    /**
     * Table relationshipe
     * @var array
     */
    protected array $relations = [];

    /**
     * Initiate model
     */
    public function __construct()
    {
        $this->mapper = new ObjectMapper($this);
    }

    /**
     * Destruct this model
     */
    public function __destruct() {}

    /**
     * Assign value to the given model property
     * Issue 57
     *
     * @param string $key   The model property
     * @param mixed  $value The property value
     */
    public function set(string $key, array $value)
    {
        $this->$key = $value;
    }

    /**
     * Here we set the return rows from database as
     * models rows
     *
     * @param string $key
     * @param array $value
     * @return void
     */
    public function setModelRows(string $key, array $value)
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
        if (isset($this->$property)) {
            return $this->$property;
        }
    }

    /**
     * Magically call mapping object method
     *
     * Issue 80
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
