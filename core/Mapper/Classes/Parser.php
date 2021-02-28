<?php

namespace Core\Mapper\Classes;

class Parser
{
    private array $data;

    public function __construct(array $data=[])
    {
        $this->data = $data;
    }

    /**
     * Build a query condition
     *
     * @return string
     */
    public function buildQueryCondition()
    {
        $condition = "";

        if (count($this->data) > 0) {
            foreach ($this->data as $key => $column) {
                $condition .= "$key='{$column}'";
            }
        }

        return $condition;
    }
}
