<?php

namespace Core\Model\Traits;

use Core\Iterators\HasManyRowIterator;

/**
 * Collection of other model methods
 *
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
     * Limitation of result in query
     */
    protected int $limit = 0;

    /**
     * Offset for query
     * @var int
     */
    protected int $skip = 0;

    /**
     * Sortation of result in query
     */
    protected string $orderBy = '';

    /**
     * Use to check if row exists
     */
    protected bool $exists = false;

    // Return the query result
    public function queryResult(): object
    {
        return $this->queryResult;
    }

    // Return query data
    #[ModelTrait('fetch')]
    public function fetch()
    {
        $rows = $this->fetchRows();
        $this->setProperty('rows', $rows);
        return $this;
    }

    /**
     * Check if the row exists
     *
     * @return object
     */
    public function exists()
    {
        return $this->mapper->exists();
    }

    # Check internally if exists
    #[ModelTrait('isExists')]
    public function isExists(): bool
    {
        $this->exists = true;
        return $this;
    }

    /**
     * Find rows
     * @param integer $id
     */
    public function find(int $id): object
    {
        $model = $this->mapper->find($id);

        # Here we check if it want to return the model
        # when exists
        if ($this->exists === true) {
            return $model;
        }

        return $this;
    }

    /**
     * Delete row
     *
     * @return boolean
     */
    public function delete()
    {
        return $this->mapper->delete($this);
    }

    /**
     * Set order by
     * @param string $order
     */
    public function orderBy(string $order = '')
    {
        $this->orderBy = $order;
    }

    /**
     * Reurn mysql query string
     *
     * @return string
     */
    public function query()
    {
        return $this->mapper->query();
    }

    /**
     * Set limit about getting database data
     * @param integer $limit
     */
    public function take(int $limit = 0)
    {
        $this->limit = $limit;
    }

    /**
     * Skip rows
     *
     * @param  integer $skip
     * @return object
     */
    public function skip(int $skip = 0)
    {
        $this->skip = $skip;
        return $this;
    }

    /**
     * Setup where condition
     * @param  string $columnName
     * @param  string $value
     */
    public function where($columnName, $value)
    {
        $this->wheres[$columnName] = $value;
    }

    # Return the the model with resulting data
    #[ModelTrait('get')]
    public function get(): object|null
    {
        return $this->mapper->get($this);
    }

    /**
     * Return the first index of the array or
     * As opposed to get() method, first() perform get and only return the first row
     */
    #[ModelTrait('first')]
    public function first(): object|null
    {
        if (count($this->rows) > 0) {
            return $this->rows[0];
        }

        self::get();
        return $this->rows[0] ?? null;
    }

    /**
     * Update table row
     * @param  array  $data
     */
    #[ModelTrait('update')]
    public function update(array $data = [], $return = false): bool
    {
        return $this->mapper->update($this, $data, $return);
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

        $result = $this->mapper->update($this, $data, $return);

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

    /**
     * Return last insert ID
     * @return int
     */
    public function insertId()
    {
        $this->id;
    }

    /**
     * Return a model or rows containing it's many relations
     * @param  boolean $returnModel
     */
    public function withMany(bool $returnModel=false): array|object
    {
        foreach (new HasManyRowIterator($this) as $key => $row) {
            foreach ($row as $secondKey => $value) {
                $this->rows[$key]->$secondKey = $value;
            }
        }

        if ($returnModel === true) {
            return $this;
        }

        return $this->rows;
    }

    /**
     * Return wheres container
     *
     * @return array
     */
    private function getWheres()
    {
        return $this->wheres;
    }
}
