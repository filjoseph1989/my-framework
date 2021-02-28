<?php

namespace Core\Mapper\Traits;

use Core\Model\Database;

trait ObjectMapperQueriesTrait
{
    /**
     * Prepare delete query
     * @param  object $model
     */
    private function prepareDeleteQuery(object &$model): string
    {
        return "DELETE FROM {$this->table} WHERE `id`={$model->rows[0]->id}";
    }
}
