<?php

namespace Core\Traits;

use Core\Model\Database;

/**
 * A trait use by Core\Model\ObjectMapping
 *
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMappingQueriesTrait
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
    private function prepareSelectQuery(string $wheres, string $limit = '', string $order = '')
    {
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

        return $query;
    }

    /**
     * Prepare insert query
     *
     * @param string $wheres
     * @return void
     */
    private function prepareInsertQuery(array &$data = [])
    {
        $keys   = self::prepareDataKey($data);
        $values = self::prepareInsertValues($data);
        return "INSERT INTO {$this->table} ({$keys}) VALUES ({$values})";
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
        return "UPDATE {$this->table} SET {$updateData} WHERE {$wheres}";
    }
}
