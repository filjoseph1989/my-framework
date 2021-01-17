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
