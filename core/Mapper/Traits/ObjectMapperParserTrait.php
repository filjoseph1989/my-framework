<?php

namespace Core\Mapper\Traits;

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
     * Prepare skip query
     *
     * @param  object $model
     * @return string
     */
    private function prepareSkip(object &$model)
    {
        if ($model->limit > 0) {
            return "OFFSET {$model->skip}";
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

    /**
     * Prepare insert keys and values
     *
     * @param  array  $data
     * @return array
     */
    private function prepareInsertData(array &$data=[])
    {
        $keys   = [];
        $values = [];

        foreach ($data as $key => $value) {
            $value    = $this->database->scape($value);
            $values[] = "'{$value}'";
            $keys[]   = $key;
        }

        return [
            'keys'   => implode(',', $keys),
            'values' => implode(',', $values)
        ];
    }
}
