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
}
