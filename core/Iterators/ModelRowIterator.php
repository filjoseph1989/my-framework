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
    public function rewind(): object
    {
        return reset($this->rows);
    }

    // Modify values of current index of row
    public function current(): object
    {
        return current($this->rows);
    }

    // Return current array index position
    public function key(): int
    {
        return key($this->rows);
    }

    // Return the next index of rows
    public function next(): bool
    {
        return next($this->rows);
    }

    // Return boolean if index exists or not
    public function valid(): bool
    {
        return key($this->rows) !== null;
    }

    // Return the count of rows
    public function countRows(): int
    {
        return count($this->rows);
    }
}
