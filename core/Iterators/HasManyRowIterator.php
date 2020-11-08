<?php

namespace Core\Iterators;

/**
 * Iterate an object
 */
class HasManyRowIterator implements \Iterator
{
    /**
     * Containers of rows
     */
    protected array $rows = [];

    /**
     * A model passed
     */
    protected object $model;

    /**
     * A table a model has many relationship with
     */
    protected object $table;

    /**
     * Accept model at initialization
     *
     * @param Core\Model $model
     */
    public function __construct(object $model)
    {
        $this->model = $model;
        $this->rows  = $model->rows ?? [];
    }

    /**
     * Reset index to zero (0)
     *
     * @return int
     */
    public function rewind()
    {
        return reset($this->rows);
    }

    /**
     * Modify values of current index
     * of row
     *
     * @return array
     */
    public function current()
    {
        $hasMany = $this->model->hasMany;
        $rows    = [];

        foreach ($hasMany as $key => $many) {
            $model = (new $many[0])->where($many[1], current($this->rows)->id)->get();
            $rows[$key] = $model;
        }

        return $rows;
    }

    /**
     * Return current array index position
     *
     * @return int
     */
    public function key()
    {
        return key($this->rows);
    }

    /**
     * Return the next index of rows
     *
     * @return array
     */
    public function next()
    {
        return next($this->rows);
    }

    /**
     * Return boolean if index exists or not
     *
     * @return boolean
     */
    public function valid()
    {
        return key($this->rows) !== null;
    }

    /**
     * Return the count of rows
     *
     * @return int
     */
    public function countRows()
    {
        return count($this->rows);
    }
}
