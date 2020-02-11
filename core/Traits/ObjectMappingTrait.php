<?php

namespace Core\Traits;

use Core\Model\Database;

/**
 * A trait use by Core\Model\ObjectMapping
 */
trait ObjectMappingTrait
{
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
     * Return the object result after query
     *
     * @param  array  $query
     * @return object
     */
    public function get(array $wheres)
    {
        self::prepare($wheres);

        return $this->rows;
    }

    /**
     * Prepare the query and get resulting data
     *
     * @param  array  $wheres
     * @return void
     */
    private function prepare(array $wheres)
    {
        if ($this->database->isConnected()) {
            $wheres  = self::prepareWhere($wheres);
            $query   = self::prepareSelect($wheres);
            $results = $this->database->query($query);

            $count = $this->database->count();

            if ($count == -1 || $count == 0) {
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
     * Building the where condition
     *
     * @param  array  $wheres
     * @return string
     */
    private function prepareWhere(array $wheres)
    {
        $whereQuery = [];

        foreach ($wheres as $key => $value) {
            $whereQuery[] = "$key='$value'";
        }

        return implode(' AND ', $whereQuery);
    }
}
