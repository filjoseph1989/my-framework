<?php

namespace Core\Traits;

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
     * Sortation of result in query
     */
    protected string $orderBy = '';

    /**
     * Delete row
     *
     * @return
     */
    public function delete()
    {
        if (count($this->rows) > 0) {
            return $this->mapper->delete($this);
        }
    }

    /**
     * Set order by
     *
     * @param string $order
     * @return model
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
     * @return model
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
     * Setup where condition
     *
     * @param  string $columnName
     * @param  string $value
     * @return model
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
     * Issue 65
     *
     * @return object
     */
    public function first()
    {
        if (count($this->rows) > 0) {
            return (new ModelFirst($this))->first();            
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
     * Return wheres container
     *
     * @return array
     */
    private function getWheres()
    {
        return $this->wheres;
    }
}
