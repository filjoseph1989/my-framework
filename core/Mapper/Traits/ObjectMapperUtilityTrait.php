<?php

namespace Core\Mapper\Traits;

/**
 * List of utility methods
 */
trait ObjectMapperUtilityTrait
{
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

    /**
     * Determine if there's a column in the database table
     *
     * @return boolean
     */
    private function isColumnEmpty(object &$model, &$condition)
    {
        $this->query = self::prepareSelectQuery($condition);

        $this->database->query($this->query);
        $this->count = $this->database->count();

        if ($this->count <= 0) {
            return true;
        }

        return false;
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

        array_walk_recursive($rows, function (&$value, $key) {
            $value = utf8_decode($value);
        });

        if ($this->currentOperation == 'create') {
            $this->model->wasRecentlyCreated = true;
            self::setModelProperty($rows);
        }

        return json_decode(json_encode($rows));
    }

    /**
     * Set model property
     *
     * @param array $rows
     */
    private function setModelProperty(array $rows = [])
    {
        foreach ($rows[0] as $key => $value) {
            $this->model->$key = $value;
        }
    }
}
