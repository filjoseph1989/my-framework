<?php

namespace Core\Traits;

use Core\Model\Database;

/**
 * A trait use by Core\Model\ObjectMapper
 *
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMapperParserTrait
{
    /**
     * Prepare order by string
     *
     * @param object $model
     * @return string
     */
    private function prepareOrderBy(object &$model)
    {
        if (strlen($model->orderBy) > 0) {
            return "ORDER BY {$model->orderBy}";
        }

        return "";
    }

    /**
     * Prepare limit string
     *
     * @param object $model
     * @return string
     */
    private function prepareLimit(object &$model)
    {
        if ($model->limit > 0) {
            return "LIMIT {$model->limit}";
        }

        return "";
    }

    /**
     * Building the where condition
     *
     * @param  object $model
     * @return string
     */
    private function prepareWhere(object &$model)
    {
        if (count($model->wheres) == 0) {
            return "";
        }

        return self::walkThroughWheres($model);
    }

    /**
     * Prepare data
     *
     * @param  array  $data
     * @return string
     */
    private function prepareData(array &$data)
    {
        $values = [];

        foreach ($data as $key => $value) {
            $value = self::scape($value);
            $values[] = "{$key}='{$value}'";
        }

        return implode(',', $values);
    }

    /**
     * Prepare insert values
     *
     * @param array $data
     * @return string
     */
    private function prepareInsertValues(array $data = [])
    {
        $values = [];

        foreach ($data as $key => $value) {
            $value = self::scape($value);
            $values[] = "'{$value}'";
        }

        return implode(',', $values);
    }

    /**
     * Prepare data key
     *
     * @param  array  $data
     * @return string
     */
    private function prepareDataKey(array $data = [])
    {
        $keys = [];

        foreach ($data as $key => $value) {
            $keys[] = $key;
        }

        return implode(',', $keys);
    }

    /**
     * Traversing the given where array
     *
     * @param  array  $wheres
     * @return string
     */
    private function walkThroughWheres(object &$model)
    {
        $whereQuery = [];
        foreach ($model->wheres as $key => $value) {
            $value        = self::scape($value);
            $whereQuery[] = "$key='$value'";
        }

        return implode(' AND ', $whereQuery);
    }
}
