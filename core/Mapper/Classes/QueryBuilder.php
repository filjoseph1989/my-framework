<?php

namespace Core\Mapper\Classes;

use Core\Mapper\Mappers\PsqlMapper;

class QueryBuilder
{
    private array $data;
    private object $database;
    private object $model;

    public function __construct(PsqlMapper $mapper)
    {
        $this->data     = $data ?? [];
        $this->database = $mapper->database;
        $this->model    = $mapper->model;
    }

    public function select()
    {
        $query = "select * from {$this->model->table}";
        return $this->database->query($query);
    }
}
