<?php

namespace Core\Mapper\Traits;

use Core\Model\Database;
use Core\Mapper\Classes\Parser;
use Core\Mapper\Classes\PrepareData;
use Core\Mapper\Traits\ObjectMapperPrepareDataTrait;
use Core\Mapper\Traits\ObjectMapperQueriesTrait;
use Core\Mapper\Traits\ObjectMapperUtilityTrait;

/**
 * A trait use by Core\Model\ObjectMapper
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMapperTrait
{
    use ObjectMapperPrepareDataTrait;
    use ObjectMapperQueriesTrait;
    use ObjectMapperUtilityTrait;

    private string $currentOperation = '';
    private int $id;

    # Count container of successfull query
    protected int $count = 0;

    # The query string container
    protected string $query = "";

    # Tell if return array or object
    protected bool $toArray = false;

    # Containers of query result
    protected array $rows = [];

    /**
     * Create new record
     * @param array $wheres
     * @param array $data
     */
    #[ObjectMapperTrait('create')]
    public function create(array $data = [], $return = false): object
    {
        $data = $data[0];
        $this->currentOperation = 'create';

        if ($this->database->isConnected()) {
            self::setDates($data);

            $data = new PrepareData($this->model, $this->database, $data);
            $data->create();

            $results     = $this->database->query($this->query = $data->getQueryString());
            $this->count = $this->database->count();
            $this->id    = $this->database->insertId();

            if ($this->count <= 0) {
                return self::addToLog("Create is not successfull");
            }

            if (!$results) {
                return self::addToLog("Create is not successfull");
            }

            if ($return) {
                return array_shift(self::get());
            }

            return self::find($this->id);
        }
    }

    # Return the last generated ID when create method was called
    #[ObjectMapperTrait('getCreatedId')]
    public function getCreatedId(): int
    {
        return $this->id;
    }

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
     * @param  int $id Table ID
     */
    #[ObjectMapperTrait('find')]
    public function find(int $value): object
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
            $prepareData->produce();
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

    # Return query string run previously
    public function query(): string
    {
        return $this->query;
    }

    /**
     * Set default date created_at and updated_at
     * @param array $data
     */
    private function setDates(array &$data): void
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
    }
}
