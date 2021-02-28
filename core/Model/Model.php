<?php

namespace Core\Model;

use Core\Mapper\ObjectMapper;
use Core\Model\Traits\ModelTrait;
use Core\Traits\DebugTrait;

class Model
{
    use ModelTrait;
    use DebugTrait;

    // The mapping object
    protected object $mapper;
    protected array $rows = [];
    public $wasRecentlyCreated = false;

    public function __construct()
    {
        $this->mapper = new ObjectMapper($this);
    }

    public function __destruct() {}

    // Return the mapped model
    public function map($model, $row): object
    {
        return $this->mapper->map($model, $row);
    }

    /**
     * Assign value to the given model property
     * @param string $key   The model property
     * @param mixed  $value The property value
     */
    public function setProperty(string $key, mixed $value)
    {
        $this->$key = $value;
    }

    /**
     * Remove property from model
     * @param string $prop  The model property
     */
    public function unsetProperty($prop)
    {
        unset($this->$prop);
    }

    // Return the array of rows
    public function getRows(): array
    {
        return $this->rows;
    }

    // Return a count of rows
    public function countRows(): int
    {
        return count($this->rows);
    }

    // Return the count of hasOne property
    public function countHasOne(): int
    {
        if (!is_null(self::getProperty('hasOne'))) {
            return count($this->hasOne);
        }

        return 0;
    }

    // Return the count of hasMany property
    public function countHasMany(): int
    {
        if (!is_null(self::getProperty('hasMany'))) {
            return count($this->hasMany);
        }

        return 0;
    }

    /**
     * Magically call mapping object method
     *
     * Issue 80
     *
     * @param  string $method    Method name
     * @param  array  $arguments The method parameters
     */
    public function __call($method, $arguments): mixed
    {
        if (!method_exists($this->mapper, $method)) {
            return null;
        }

        return $this->mapper->$method($arguments);
    }

    /**
     * Magically call the property
     * @param  mixed $property
     */
    public function __get($property): mixed
    {
        return self::getProperty($property);
    }

    /**
     * Return property
     * @param string $property
     */
    private function getProperty(string $property = ''): mixed
    {
        if (isset($this->$property)) {
            return $this->$property;
        }

        return null;
    }
}
