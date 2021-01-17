<?php

namespace Core\Mapper\Traits;

use Core\Model\Database;
use Core\Mapper\Classes\Parser;
use Core\Mapper\Classes\PrepareData;
use Core\Mapper\Traits\ObjectMapperParserTrait;
use Core\Mapper\Traits\ObjectMapperPrepareDataTrait;
use Core\Mapper\Traits\ObjectMapperQueriesTrait;
use Core\Mapper\Traits\ObjectMapperUtilityTrait;

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
    use ObjectMapperUtilityTrait;

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
     *
     * @param  int $id Table ID
     * @return object
     */
    public function find(int $value)
    {
        return self::prepareFind($value);
    }

    /**
     * look for a columns or create if not exists
     *
     * @param  array  $columns
     * @return object
     */
    public function findOrCreate(array $columns = [])
    {
        if (!self::exists($columns)) {
            $prepareData = new PrepareData($this->model, $this->database, $this->table, $columns[0]);
            $prepareData->create();
            return self::find($prepareData->insertedId());
        }
    }

    /**
     * Check if the column exists
     *
     * @param  array  $columns
     * @return boolean
     */
    public function exists(array $columns=[])
    {
        if ($this->database->isConnected()) {
            $condition = (new Parser($columns[0]))->buildQueryCondition();
            $this->query = self::prepareSelectQuery($condition);
            $this->query = "SELECT EXISTS ({$this->query})";

            $rows = $this->database->query($this->query);
            $rows = self::fetchRows($rows);
            $rows = get_object_vars($rows[0]);
            foreach ($rows as $key => $value) {
                return $value;
            }
        }
    }

    /**
     * Return resulting column from the given conditions
     *
     * @param integer $value
     * @param array $columns
     * @return mixed
     */
    public function findByColumn(array $columns = [])
    {
        return self::prepareFindByColumn($columns[0]);
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
     * @param  array  $query
     */
    public function get(object &$model): object|null
    {
        return self::prepareGet($model);
    }

    /**
     * Update database table
     *
     * @param array $data
     * @return object
     */
    public function update(object &$model, array &$data = [], $return = false)
    {
        return self::performUpdate($model, $data, $return);
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
        return self::performCreate($data[0], $return);
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
}
