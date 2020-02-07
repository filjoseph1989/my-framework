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
    public function get(array $query)
    {
        return (object) $query;
    }
}
