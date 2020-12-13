<?php

namespace Core\Iterators;

class HasManyRowIterator implements \Iterator
{
    # Containers of rows
    protected array $rows = [];

    # A model passed
    protected object $model;

    # A table a model has many relationship with
    protected object $table;

    /**
     * Accept model at initialization
     * @param Core\Model $model
     */
    public function __construct(object $model)
    {
        $this->model = $model;
        $this->rows  = $model->rows ?? [];
    }

    # Reset index to zero (0)
    public function rewind(): object
    {
        return reset($this->rows);
    }

    # Modify values of current index of row
    public function current(): array
    {
        $hasMany = $this->model->hasMany;
        $rows    = [];

        foreach ($hasMany as $key => $many) {
            $model = (new $many[0])
                ->where($many[1], current($this->rows)->id)
                ->get();
            $rows[$key] = $model;
        }

        return $rows;
    }

    # Return current array index position
    public function key(): int
    {
        return key($this->rows);
    }

    # Return the next index of rows
    public function next(): object|bool
    {
        return next($this->rows);
    }

    # Return boolean if index exists or not
    public function valid(): bool
    {
        return key($this->rows) !== null;
    }

    # Return the count of rows
    public function countRows(): int
    {
        return count($this->rows);
    }
}
