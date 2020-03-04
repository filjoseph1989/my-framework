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
        return $this->mapper->get($this->wheres);
    }

    /**
     * Return the first index of the array
     *
     * @return object
     */
    public function first()
    {
        $data = self::get();
        return array_shift($data);
    }

    /**
     * Update table row
     *
     * @param  array  $data
     * @return void
     */
    public function update(array $data = [])
    {
        return $this->mapper->update($this->wheres, $data);
    }
}
