<?php

namespace Core\Traits;

trait ModelTrait
{
    protected array $wheres = [];

    public function where($columnName, $value)
    {
        $this->wheres[$columnName] = $value;
        return $this;
    }

    public function get()
    {
        # mao ni motawag og query builder tapos ipasa ang query
        # sa object mapper
        return $this->mapper->get($this->wheres);
    }
}
