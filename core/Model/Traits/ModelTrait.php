<?php

namespace Core\Model\Traits;

use Core\Iterators\HasManyRowIterator;
use Core\Model\ModelFirst;

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

    /**
     * Check if the row exists
     *
     * @return object
     */
    public function exists()
    {
        return $this->mapper->exists();
    }

    /**
     * Check internally if exists
     *
     * @return boolean
     */
    public function isExists()
    {
        $this->exists = true;
        return $this;
    }

    /**
     * Find rows
     *
     * @param integer $id
     * @return object
     */
    public function find(int $id)
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
     *
     * @param string $order
     * @return object
     */
    public function orderBy(string $order = '')
    {
        $this->orderBy = $order;
        return $this;
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
     *
     * @param integer $limit
     * @return object
     */
    public function take(int $limit = 0)
    {
        return self::limit($limit);
    }

    /**
     * Set limit of a query
     *
     * @param  integer $limit
     * @return object
     */
    public function limit(int $limit = 0)
    {
        $this->limit = $limit;
        return $this;
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
     *
     * @param  string $columnName
     * @param  string $value
     * @return object
     */
    public function where($columnName, $value)
    {
        $this->wheres[$columnName] = $value;
        return $this;
    }

    /**
     * Return the resulting data
     *
     * @return mixed
     */
    public function get()
    {
        return $this->mapper->get($this);
    }

    /**
     * Return the first index of the array
     *
     * @return object
     */
    public function first()
    {
        if (count($this->rows) > 0) {
            return $this->rows[0];
        }

        $rows = self::get();
        return (new ModelFirst($rows))->first();
    }

    /**
     * Update table row
     *
     * @param  array  $data
     * @return boolean
     */
    public function update(array $data = [], $return = false)
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
     * Return a model containing a one to many relationship
     */
    public function withMany(string $table='', bool $returnModel=false)
    {
        $this->withMany = $table;

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
