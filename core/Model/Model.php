<?php

namespace Core\Model;

use Core\Mapper\ObjectMapper;
use Core\Mapper\Mappers\PsqlMapper;
use Core\Model\Traits\ModelTrait;
use Core\Traits\DebugTrait;
use Core\Model\Database;

class Model
{
    use ModelTrait;
    use DebugTrait;

    protected object $mapper;
    protected array $collections = [];
    protected int $count = 0;
    public object $database;
    public bool $wasRecentlyCreated = false;
    public array $fields = [];

    public function __construct()
    {
        $this->database = new Database;
        
        switch ($this->database->getDatabaseDriver()) {
            case 'psql':
                $this->mapper = new PsqlMapper($this);
                break;
            
            default:
                $this->mapper = new ObjectMapper($this);
                break;
        }
    }

    public function __destruct() {}

    /**
     * Assign value to the given model property
     * 
     * @param string $key   The model property
     * @param mixed  $value The property value
     */
    public function setProperty(string $key, mixed $value) 
    {
        $this->$key = !is_null($value) ? $value : null;
    }

    /**
     * Add data to collections
     *
     * @param object $row
     * @return void
     */
    public function addCollections(object $row)
    {
        $this->collections[] = $row;
    }

    /**
     * Return how many models collected
     *
     * @param integer $count
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }    

    # Return the mapped model
    public function map($model, $row): object
    {
        return $this->mapper->map($model, $row);
    }
    
    /**
     * Remove property from model
     * @param string $prop  The model property
     */
    public function unsetProperty($prop)
    {
        unset($this->$prop);
    }

    # Return the array of rows
    public function getRows(): array
    {
        return $this->rows;
    }

    # Return a count of rows
    public function countRows(): int
    {
        return count($this->rows);
    }

    # Return the count of hasOne property
    public function countHasOne(): int
    {
        if (!is_null(self::getProperty('hasOne'))) {
            return count($this->hasOne);
        }

        return 0;
    }

    # Return the count of hasMany property
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
        if (method_exists($this->mapper, $method)) {
            return $this->mapper->$method($arguments);
        }
    }

    /**
     * Magically call the property
     * @param  mixed $property
     */
    public function __get($property): mixed
    {
        return $this->getProperty($property);
    }
}
