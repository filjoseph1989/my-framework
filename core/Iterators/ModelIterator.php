<?php

namespace Core\Iterators;

class ModelIterator implements \Iterator
{
    protected int $position = 0;
    protected array $rows = [];

    /**
     * Accept model at initialization
     * @param Core\Model $model
     */
    public function __construct(object $model)
    {
        $this->rows = $model->collections;
    }

    // Reset index to zero (0)
    public function rewind(): void
    {
        $this->position = 0;
    }

    // Modify values of current index of row
    public function current(): mixed
    {
        return $this->rows[$this->position];
    }

    // Return current array index position
    public function key(): mixed
    {
        return $this->position;
    }

    // Return the next index of rows
    public function next(): void
    {
        ++$this->position;
    }

    // Return boolean if index exists or not
    public function valid(): bool
    {
        return isset($this->rows[$this->position]);
    }

    // Return the count of rows
    public function countRows(): mixed
    {
        return count($this->rows);
    }
}
