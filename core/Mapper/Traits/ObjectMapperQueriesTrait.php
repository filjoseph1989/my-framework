<?php

namespace Core\Mapper\Traits;

use Core\Model\Database;

/**
 * A trait use by Core\Model\ObjectMapper
 *
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMapperQueriesTrait
{
    /**
     * Prepare delete query
     *
     * @param  object $model
     * @return string
     */
    private function prepareDeleteQuery(object &$model)
    {
        return "DELETE FROM {$this->table} WHERE `id`={$model->rows[0]->id}";
    }

    /**
     * Prepare select
     *
     * @param  string $wheres
     * @return string
     */
    private function prepareSelectQuery(
        string $wheres,
        string $limit = '',
        string $offset = '',
        string $order = ''
    ) {
        $query = "SELECT * FROM {$this->table}";

        if (!empty($wheres)) {
            $query = "{$query} WHERE {$wheres}";
        }

        if (!empty($order)) {
            $query = "{$query} {$order}";
        }

        if (!empty($limit)) {
            $query = "{$query} {$limit}";
        }

        if (!empty($offset)) {
            $query = "{$query} {$offset}";
        }

        return $query;
    }

    /**
     * Prepare insert query
     *
     * @param string $wheres
     * @return string
     */
    private function prepareInsertQuery(array &$data = [])
    {
        $newData = self::prepareInsertData($data);
        return "INSERT INTO {$this->table} ({$newData['keys']}) VALUES ({$newData['values']})";
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
        $query = "UPDATE {$this->table} SET {$updateData}";

        if ($wheres != "") {
            $query .= " WHERE {$wheres}";
        }

        return $query;
    }
}
