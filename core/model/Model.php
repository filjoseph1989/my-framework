<?php

namespace Core\model;

use Core\Model\ObjectMapping;

/**
 * Main model
 *
 * @author Fil Joseph Beluan
 */
class Model
{
    public object $Mapper;
    public array $attributes = [];
    public array $relations = [];

    public function __construct()
    {
        $this->Mapper = new ObjectMapping($this);
    }

    /**
     * Return object of the database.table form the given ID
     *
     * @param  int $id
     * @return object
     */
    public function find($id)
    {
        $mappedObject = $this->Mapper->find($id);

        return $mappedObject;
    }

    public function set($key, $value)
    {
        $this->$key = $value;
    }

    public function unsetProperty($prop)
    {
        unset($this->$prop);
    }
}
