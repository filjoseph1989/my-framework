<?php

namespace Core\Traits;

use Core\Model\Database;

/**
 * A trait use by Core\Model\ObjectMapping
 *
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMappingPrepareDataTrait
{
    /**
     * Prepare create
     *
     * @param array $data
     * @return void
     */
    private function prepareCreate(array &$data = [], $return = false)
    {
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
                debug_print_append("\nCreate is not successfull @ core\Traits\ObjectMappingTrait.php:111\n");
                debug_print_append(trace(true));
                return false;
            }

            if (!$results) {
                debug_print_append("\nCreate is not successfull @ core\Traits\ObjectMappingTrait.php:111\n");
                debug_print_append(trace(true));
                return false;
            }

            if ($return) {
                return array_shift(self::get());
            }

            return self::find([$this->id]);
        }
    }

    /**
     * Prepare updating table
     *
     * @param  array    $wheres
     * @param  array    $data
     * @param  boolean  $return
     * @return boolean|object
     */
    private function prepareUpdate(array $wheres, array $data = [], $return = false)
    {
        if ($this->database->isConnected()) {
            $condition   = self::prepareWhere($wheres);
            $this->query = self::prepareSelectQuery($condition);

            $this->database->query($this->query);
            $this->count = $this->database->count();

            if ($this->count <= 0) {
                debug_print_append("\nRow {$condition} doesn't exist @ core\Traits\ObjectMappingTrait.php:92\n");
                debug_print_append(trace(true));
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
     * @return void
     */
    private function prepareGet(object &$model)
    {
        if ($this->database->isConnected()) {
            $condition   = self::prepareWhere($model->wheres);
            $order       = self::prepareOrderBy($model);
            $limit       = self::prepareLimit($model);
            $this->query = self::prepareSelectQuery($condition, $limit, $order);
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
     * @return void
     */
    private function prepareFind(array &$value)
    {
        if ($this->database->isConnected()) {
            $condition   = "{$this->primaryKey}='{$value[0]}'";
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
     * Return the rows from database
     *
     * @param  object $results
     * @return array
     */
    private function fetchRows(object $results)
    {
        while ($row = $results->fetch_assoc()) {
            $rows[] = $row;
        }

        array_walk_recursive($rows, function (&$item, $key) {
            $item = utf8_decode($item);
        });

        return json_decode(json_encode($rows));
    }

    /**
     * Check if has count
     *
     * @return boolean
     */
    private function hasCount()
    {
        $this->count = $this->database->count();

        if ($this->count == -1 || $this->count == 0) {
            return null;
        }

        return true;
    }

    /**
     * Map results into model object
     *
     * @param object $rows
     * @return void
     */
    private function mapRows(object $rows)
    {
        $rows = self::fetchRows($rows);

        foreach ($rows as $key => $row) {
            if ($this->toArray) {
                $this->rows[] = $row; # Issue 68
            } else {
                $rows[$key] = self::map($this->model, $row);
            }
        }

        $this->model->setModelRows('rows', $rows);
    }
}
