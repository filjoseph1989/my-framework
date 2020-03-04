<?php

namespace Core\Traits;

use Core\Model\Database;

/**
 * A trait use by Core\Model\ObjectMapping
 * 
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMappingTrait
{
    protected int $count = 0;

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
     * Return the object result after query
     * Issue 49
     * Issue 51
     *
     * @param  array  $query
     * @return object
     */
    public function get(array $wheres = [])
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
    public function update(array &$wheres = [], array &$data = [])
    {
        return self::prepareUpdate($wheres, $data);
    }

    /**
     * Prepare updating table
     *
     * @param  array  $wheres
     * @param  array  $data
     * @return boolean
     */
    private function prepareUpdate(array $wheres, array $data = [])
    {
        if ($this->database->isConnected()) {
            $wheres  = self::prepareWhere($wheres);
            $query   = self::prepareUpdateQuery($wheres, self::prepareUpdateData($data));
            $results = $this->database->query($query);

            if (!$results) {
                return null;
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
    private function prepareGet(array $wheres)
    {
        if ($this->database->isConnected()) {
            $wheres  = self::prepareWhere($wheres);
            $query   = self::prepareSelect($wheres);
            $results = $this->database->query($query);

            $this->count = $this->database->count();

            if ($this->count == -1 || $this->count == 0) {
                return null;
            }

            foreach ($results as $row) {
                $this->rows[] = (object) $row;
            }
        }
    }

    /**
     * Prepare select
     *
     * @param  string $wheres
     * @return string
     */
    private function prepareSelect(string $wheres)
    {
        return "select * from {$this->table} where {$wheres};";
    }

    /**
     * Prepare update query
     *
     * @param string $wheres
     * @param string $updateData
     * @return string
     */
    private function prepareUpdateQuery(string $wheres, string $updateData)
    {
        return "UPDATE {$this->table} SET {$updateData} WHERE {$wheres};";
    }

    /**
     * Building the where condition
     *
     * @param  array  $wheres
     * @return string
     */
    private function prepareWhere(array $wheres)
    {
        $whereQuery = [];

        foreach ($wheres as $key => $value) {
            $value = self::scape($value);
            $whereQuery[] = "$key='$value'";
        }

        return implode(' AND ', $whereQuery);
    }

    /**
     * Prepare update data
     *
     * @param  array  $data
     * @return string
     */
    private function prepareUpdateData(array $data)
    {
        $updateData = [];

        foreach ($data as $key => $value) {
            $value = self::scape($value);
            $updateData[] = "{$key}='{$value}'";
        }

        return implode(',', $updateData);
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
