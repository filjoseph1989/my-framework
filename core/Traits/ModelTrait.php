<?php

namespace Core\Traits;

use Core\Model\ModelFirst;

/**
 * Collection of other model methods
 * @author Fil Joseph Beluan <filjoseph22@gmail.com>
 */
trait ModelTrait
{
    /**
     * Wheres container
     * @var array
     */
    protected array $wheres = [];

    /**
     * Tell if return as array or object
     *
     * @var boolean
     */
    protected $toArray = false;

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
     * Issue 65
     *
     * @return object
     */
    public function first()
    {
        $rows = self::get();
        return (new ModelFirst($rows))->first();
    }

    /**
     * Update table row
     *
     * @param  array  $data
     * @return void
     */
    public function update(array $data = [], $return = false)
    {
        return $this->mapper->update($this->wheres, $data, $return);
    }

    /**
     * Update row if exist otherwise
     * create
     * 
     * Issue 71
     *
     * @param  array   $data
     * @param  boolean $return
     * @return object|boolean
     */
    public function updateOrCreate(array $data = [], $return = false)
    {
        if ($this->toArray) {
            $this->mapper->toArray();
        }

        $result = $this->mapper->update($this->wheres, $data, $return);

        if ($result) {
            return $result;
        }

        return $this->mapper->create($data);
    }

    /**
     * Tell to return array
     *
     * @return object
     */
    public function toArray()
    {
        $this->toArray = true;
        return $this;
    }
}
