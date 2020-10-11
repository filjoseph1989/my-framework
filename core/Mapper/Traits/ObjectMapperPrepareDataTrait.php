<?php

namespace Core\Mapper\Traits;

use Core\Mapper\Classes\Parser;
use Core\Model\Database;

/**
 * A trait use by Core\Model\ObjectMapper
 *
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMapperPrepareDataTrait
{
    private string $currentOperation = '';

    /**
     * Prepare delete
     *
     * @param object $model
     * @return boolean|object
     */
    private function prepareDelete(object &$model)
    {
        if ($this->database->isConnected()) {
            $this->query = self::prepareDeleteQuery($model);
            return $this->database->query($this->query);
        }
    }

    /**
     * Prepare create
     * Issue 87
     *
     * @param array $data
     * @return object
     */
    private function performCreate(array &$data = [], $return = false)
    {
        $this->currentOperation = 'create';

        if ($this->database->isConnected()) {
            if (!isset($data['created_at'])) {
                $data['created_at'] = date('Y-m-d H:i:s');
            }
            if (!isset($data['updated_at'])) {
                $data['updated_at'] = date('Y-m-d H:i:s');
            }

            $this->query = self::prepareInsertQuery($data);
            $results     = $this->database->query($this->query);
            $this->count = $this->database->count();
            $this->id    = $this->database->insertId();

            if ($this->count <= 0) {
                debug_print_append("\nCreate is not successfull\n");
                debug_print_append(trace(true));
                return false;
            }

            if (!$results) {
                debug_print_append("\nCreate is not successfull\n");
                debug_print_append(trace(true));
                return false;
            }

            if ($return) {
                return array_shift(self::get());
            }

            return self::find($this->id);
        }
    }

    /**
     * Prepare updating table
     *
     * @param  object  $model
     * @param  array   $data
     * @param  boolean $return
     * @return object
     */
    private function performUpdate(object &$model, array &$data = [], $return = false)
    {
        if ($this->database->isConnected()) {
            $condition = self::prepareWhere($model);

            if (self::isColumnEmpty($model, $condition)) {
                return false;
            }

            if (!isset($data['updated_at'])) {
                $data['updated_at'] = date('Y-m-d H:i:s');
            }

            $this->query = self::prepareUpdateQuery($condition, self::prepareData($data));
            $results     = $this->database->query($this->query);

            if (!$results) {
                return null;
            }

            if ($return) {
                $results = self::get($wheres);
                return array_shift($results);
            }

            return $results;
        }
    }

    /**
     * Prepare the query and get resulting data
     *
     * @param  array  $wheres
     * @return object
     */
    private function prepareGet(object &$model)
    {
        if ($this->database->isConnected()) {
            $condition   = self::prepareWhere($model);
            $order       = self::prepareOrderBy($model);
            $limit       = self::prepareLimit($model);
            $offset      = self::prepareSkip($model);
            $this->query = self::prepareSelectQuery($condition, $limit, $offset, $order);
            $rows        = $this->database->query($this->query);

            if (is_null(self::hasCount())) {
                return null;
            }

            self::mapRows($rows);

            return $this->model;
        }
    }

    /**
     * Prepare find
     *
     * @param array $value
     * @return object
     */
    private function prepareFind(int &$value)
    {
        if ($this->database->isConnected()) {
            $condition   = "{$this->primaryKey}='{$value}'";
            $this->query = self::prepareSelectQuery($condition);
            $rows        = $this->database->query($this->query);

            if (is_null(self::hasCount())) {
                return null;
            }

            self::mapRows($rows);

            return $this->model;
        }
    }

    /**
     * Prepare find using columns given
     *
     * @param integer $value
     * @param array $columns
     * @return mixed
     */
    private function prepareFindByColumn(array &$columns)
    {
        if ($this->database->isConnected()) {
            $condition = (new Parser($columns))->buildQueryCondition();
            $this->query = self::prepareSelectQuery($condition);

            if ($this->model->exists === true) {
                $this->query = "SELECT EXISTS ({$this->query})";
            }

            $rows = $this->database->query($this->query);

            if ($this->model->exists === true) {
                $rows = self::fetchRows($rows);
                $rows = get_object_vars($rows[0]);
                foreach ($rows as $key => $value) {
                    return $value;
                }
            }

            if (is_null(self::hasCount())) {
                return null;
            }

            self::mapRows($rows);

            return $this->model;
        }
    }
}
