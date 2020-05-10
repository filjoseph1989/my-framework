<?php

namespace Core\Mapper\Traits;

use Core\Model\Database;
use Core\Mapper\Traits\ObjectMapperParserTrait;
use Core\Mapper\Traits\ObjectMapperPrepareDataTrait;
use Core\Mapper\Traits\ObjectMapperQueriesTrait;

/**
 * A trait use by Core\Model\ObjectMapper
 *
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMapperTrait
{
    use ObjectMapperParserTrait;
    use ObjectMapperPrepareDataTrait;
    use ObjectMapperQueriesTrait;

    /**
     * Count container of successfull query
     * @var int
     */
    protected int $count    = 0;

    /**
     * The query string container
     * @var string
     */
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
     * Delete row in a table
     *
     * @param  object $model
     * @return boolean
     */
    public function delete(object &$model)
    {
        if (count($model->rows) > 0) {
            return self::prepareDelete($model);
        }

        return false;
    }

    /**
     * Find row using ID
     * Issue 63
     *
     * @param  int $id Table ID
     * @return object
     */
    public function find(int $value)
    {
        return self::prepareFind($value);
    }

    /**
     * Return resulting column from the given conditions
     *
     * @param integer $value
     * @param array $columns
     * @return mixed
     */
    public function findByColumn(int $value, array $columns = [])
    {
        return self::prepareFindByColumn($value, $columns);
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
    public function get(object &$model)
    {
        return self::prepareGet($model);
    }

    /**
     * Update database table
     *
     * @param array $data
     * @return object
     */
    public function update(object &$model, array $data = [], $return = false)
    {
        return self::prepareUpdate($model, $data, $return);
    }

    /**
     * Create new record
     *
     * @param array $wheres
     * @param array $data
     * @return object
     */
    public function create(array $data = [], $return = false)
    {
        return self::prepareCreate($data[0], $return);
    }

    /**
     * Return query string run previously
     *
     * @return string
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * Scape any value given
     *
     * @param mixed $value
     * @return mixed
     */
    private function scape($value)
    {
        return $this->database->scape($value);
    }
}
