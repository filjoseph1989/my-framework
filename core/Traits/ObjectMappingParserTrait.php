<?php

namespace Core\Traits;

use Core\Model\Database;

/**
 * A trait use by Core\Model\ObjectMapping
 *
 * @author fil beluan <filjoseph22@gmail.com>
 */
trait ObjectMappingParserTrait
{
    /**
     * Prepare limit string
     *
     * @param object $model
     * @return void
     */
    private function prepareLimit(object &$model)
    {
        if ($model->limit > 0) {
            return "limit {$model->limit}";
        }

        return "";
    }
    /**
     * Building the where condition
     *
     * @param  array  $wheres
     * @return string
     */
    private function prepareWhere(array $wheres)
    {
        if (empty($wheres)) {
            return "";
        }

        return self::walkThroughWheres($wheres);
    }

    /**
     * Prepare data
     *
     * @param  array  $data
     * @return string
     */
    private function prepareData(array $data = [])
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
     * @return void
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
    private function walkThroughWheres(array $wheres)
    {
        $whereQuery = [];
        foreach ($wheres as $key => $value) {
            $value = self::scape($value);
            $whereQuery[] = "$key='$value'";
        }

        return implode(' AND ', $whereQuery);
    }
}
