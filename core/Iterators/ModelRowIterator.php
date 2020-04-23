<?php

namespace Core\Iterators;

/**
 * Iterate an object
 *
 * Task 4
 */
class ModelRowIterator implements \Iterator
{
    /**
     * Containers of rows
     */
    protected array $rows = [];

    /**
     * Accept model at initialization
     *
     * @param Core\Model $model
     */
    public function __construct(object $model)
    {
        $this->rows = $model->rows;
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
        return current($this->rows);
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
