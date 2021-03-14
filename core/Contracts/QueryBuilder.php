<?php

namespace Core\Contracts;

// A contract to protect the QueryBuilderClass
interface QueryBuilder
{
    public function get();

    public function take(string $limit);

    public function order(string $table, string $column, string $sort);

    public function pick(string $table, string $column);

    public function from(string $table='');

    public function join(string $table1, string $table2, string $condition);
}
