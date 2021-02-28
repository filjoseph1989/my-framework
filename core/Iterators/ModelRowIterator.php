<?php

namespace Core\Iterators;

class ModelRowIterator implements \Iterator
{
    protected array $rows = [];

    /**
     * Accept model at initialization
     * @param Core\Model $model
     */
    public function __construct(object $model)
    {
        $this->rows = $model->rows;
    }

    // Reset index to zero (0)
    public function rewind(): mixed
    {
        return reset($this->rows);
    }

    // Modify values of current index of row
    public function current(): mixed
    {
        return current($this->rows);
    }

    // Return current array index position
    public function key(): mixed
    {
        return key($this->rows);
    }

    // Return the next index of rows
    public function next(): mixed
    {
        return next($this->rows);
    }

    // Return boolean if index exists or not
    public function valid(): mixed
    {
        return key($this->rows) !== null;
    }

    // Return the count of rows
    public function countRows(): mixed
    {
        return count($this->rows);
    }
}
