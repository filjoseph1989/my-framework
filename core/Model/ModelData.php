<?php

namespace Core\Model;

class ModelData
{
    public function __construct()
    {
    }

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
}
