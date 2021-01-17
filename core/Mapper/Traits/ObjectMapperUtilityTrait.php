<?php

namespace Core\Mapper\Traits;

trait ObjectMapperUtilityTrait
{
    /**
     * Scape any value given
     * @param mixed $value
     */
    #[ObjectMapperUtilityTrait('scape')]
    private function scape($value): string
    {
        return $this->database->scape($value);
    }

    /**
     * Determine if there's a column in the database table
     * @param  object $data
     */
    #[ObjectMapperUtilityTrait('isColumnEmpty')]
    private function isColumnEmpty(object &$data): bool
    {
        $data->where();
        $data->select();
        $this->database->query($this->query = $data->getQueryString());
        $this->count = $this->database->count();

        if ($this->count <= 0) {
            return true;
        }

        return false;
    }

    # Check if has count
    #[ObjectMapperUtilityTrait('hasCount')]
    private function hasCount(): bool
    {
        $this->count = $this->database->count();

        if ($this->count == -1 || $this->count == 0) {
            return false;
        }

        return true;
    }

    /**
     * Map results into model object
     * @param object $rows
     */
    #[ObjectMapperUtilityTrait('mapRows')]
    private function mapRows(object $rows)
    {
        $rows = self::fetchRows($rows);

        if (is_null($rows)) {
            return null;
        }

        foreach ($rows as $key => $row) {
            $rows[$key] = self::map($this->model, $row);
        }

        $this->model->setModelRows('rows', $rows);
    }

    /**
     * Return the rows from database
     * @param  object $results
     */
    private function fetchRows(object $results): array|null
    {
        while ($row = $results->fetch_assoc()) {
            $rows[] = $row;
        }

        if (!isset($rows)) {
            return null;
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
