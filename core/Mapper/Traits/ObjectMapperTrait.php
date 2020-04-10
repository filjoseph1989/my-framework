<?php

namespace Core\Mapper\Traits;

use Core\Model\Database;
<<<<<<<< HEAD:core/Mapper/Traits/ObjectMapperTrait.php
use Core\Mapper\Traits\ObjectMapperParserTrait;
use Core\Mapper\Traits\ObjectMapperPrepareDataTrait;
use Core\Mapper\Traits\ObjectMapperQueriesTrait;
========
<<<<<<< HEAD:core/Mapper/Traits/ObjectMapperTrait.php
use Core\Mapper\Traits\ObjectMapperParserTrait;
use Core\Mapper\Traits\ObjectMapperPrepareDataTrait;
use Core\Mapper\Traits\ObjectMapperQueriesTrait;
=======
use Core\Traits\ObjectMapperParserTrait;
use Core\Traits\ObjectMapperPrepareDataTrait;
use Core\Traits\ObjectMapperQueriesTrait;
>>>>>>> 9287905... Core: Rename objectMapping to objectMapper:core/Traits/ObjectMapperTrait.php
>>>>>>>> b81e947... Core: Moved object mapper traits to its own directory:core/Traits/ObjectMapperTrait.php

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
     * Should return an object map
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
     * Prepare find
     *
     * Issue 67
     *
     * @param array $value
     * @return void
     */
    private function prepareFind(array &$value)
    {
        if ($this->database->isConnected()) {
            $condition   = "{$this->primaryKey}='{$value[0]}'";
            $this->query = self::prepareSelectQuery($condition);
            $rows        = $this->database->query($this->query);

            $this->count = $this->database->count();

            if ($this->count == -1 || $this->count == 0) {
                return null;
            }

            $rows = self::fetchRows($rows);

            foreach ($rows as $key => $row) {
                if ($this->toArray) {
                    $this->rows[] = $row; # Issue 68
                } else {
                    $rows[$key] = self::map($this->model, $row);
                }
            }

            $this->model->setModelRows('rows', $rows);

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
    public function get(object &$model)
    {
        return self::prepareGet($model);
    }

    /**
     * Update database table
     *
     * @param array $data
     * @return void
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
     * @return void
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
     * @return void
     */
    private function scape($value)
    {
        // $value = trim($value, " \t\n\r\0\x0B");
        return $this->database->scape($value);
    }
}
