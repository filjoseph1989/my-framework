<?php

namespace Core\Mapper\Traits;

use Core\Mapper\Classes\Parser;
use Core\Mapper\Classes\PrepareData;
use Core\Model\Database;
use Core\Traits\LogTrait;

trait ObjectMapperPrepareDataTrait
{
    use LogTrait;

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
     * Prepare updating table
     * @param  object  $model
     * @param  array   $data
     * @param  boolean $return
     */
    private function performUpdate(object &$model, array &$data = [], $return = false): bool #Todo-13
    {
        if ($this->database->isConnected()) {
            if (!isset($data['updated_at'])) {
                $data['updated_at'] = date('Y-m-d H:i:s');
            }

            $prepareData = new PrepareData($model, $this->database, $data);

            if (self::isColumnEmpty($prepareData)) {
                return false;
            }

            $prepareData->updateData();
            $prepareData->update();
            $results = $this->database->query($this->query = $prepareData->getQueryString());

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
     * @param  array  $wheres
     */
    private function prepareGet(object &$model): object|null
    {
        if ($this->database->isConnected()) {
            $data = new PrepareData($model, $this->database);
            $data->where();
            $data->order();
            $data->limit();
            $data->skip();
            $data->select();

            $rows = $this->database->query(
                $this->query = $data->getQueryString()
            );

            if (is_null(self::hasCount())) {
                return null;
            }

            self::mapRows($rows);

            return $this->model;
        }

        return null;
    }

    /**
     * Prepare find
     * @param array $value
     */
    #[ObjectMapperPrepareDataTrait('prepareFind')]
    private function prepareFind(int &$value): object|null
    {
        if ($this->database->isConnected()) {
            $data = new PrepareData($this->model, $this->database);
            $data->setCondition("{$this->primaryKey}='{$value}'");
            $data->select();

            $rows = $this->database->query($this->query = $data->getQueryString());

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
