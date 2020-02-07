<?php

namespace Core\Traits;

trait ModelTrait
{
    /**
     * Wheres container
     * @var array
     */
    protected array $wheres = [];

    /**
     * Setup where condition
     *
     * @param  string $columnName
     * @param  string $value
     * @return void
     */
    public function where($columnName, $value)
    {
        $this->wheres[$columnName] = $value;
        return $this;
    }

    /**
     * Return wheres container
     *
     * @return array
     */
    public function getWheres()
    {
        return $this->wheres;
    }

    /**
     * Return the resulting data
     *
     * @return mixed
     */
    public function get()
    {
        # mao ni motawag og query builder tapos ipasa ang query
        # sa object mapper
        return $this->mapper->get($this->wheres);
    }
}
