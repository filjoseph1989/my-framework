<?php

namespace Core\Traits;

use Core\Model\Database;
use Core\Traits\ObjectMappingParserTrait;
use Core\Traits\ObjectMappingPrepareDataTrait;
use Core\Traits\ObjectMappingQueriesTrait;

/**
 * A trait use by Core\Model\ObjectMapping
 *
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMappingTrait
{
    use ObjectMappingParserTrait;
    use ObjectMappingPrepareDataTrait;
    use ObjectMappingQueriesTrait;

    protected int $count    = 0;
    protected string $query = "";

    /**
     * Tell if return array or object
     *
     * @var boolean
     */
    protected $toArray = false;

    /**
     * Containers of query result
     */
    protected array $rows = [];

    /**
     * Should return an object map
     *
     * @param  int $id Table ID
     * @return object
     */
    public function find(array $arguments)
    {
        if ($this->database->isConnected()) {
            $rows = $this->database
                ->select(strtolower($this->table), ['*'])
                ->whereArray([
                    "column"    => $this->primaryKey,
                    "value"     => $arguments[0],
                    "condition" => "=",
                ])
                ->get();

            $this->model->set('rows', $rows[0]);

            self::map($this->model, $rows);

            return $this->model;
        }
    }

    /**
     * Return the row count of sql query
     *
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * Tell to return query result as array
     *
     * @return object
     */
    public function toArray()
    {
        $this->toArray = true;
        return $this;
    }

    /**
     * Return the object result after query
     * Issue 49
     * Issue 51
     * Issue 53
     *
     * @param  array  $query
     * @return object
     */
    public function get(array &$wheres = [])
    {
        self::prepareGet($wheres);
        return $this->rows;
    }

    /**
     * Update database table
     * Issue 50
     *
     * @param array $data
     * @return void
     */
    public function update(array $wheres = [], array $data = [], $return = false)
    {
        return self::prepareUpdate($wheres, $data, $return);
    }

    /**
     * Create new record
     *
     * @param array $wheres
     * @param array $data
     * @return void
     */
    public function create(array $data = [], $return = false)
    {
        return self::prepareCreate($data, $return);
    }

    /**
     * Return query string run previously
     *
     * @return string
     */
    private function query()
    {
        return $this->query;
    }

    /**
     * Scape any value given
     *
     * @param mixed $value
     * @return void
     */
    private function scape($value)
    {
        $value = trim($value, " \t\n\r\0\x0B");
        return $this->database->scape($value);
    }
}
