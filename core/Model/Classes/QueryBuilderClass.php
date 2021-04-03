<?php

namespace Core\Model\Classes;

use Core\Contracts\QueryBuilder;

# A Query Builder
class QueryBuilderClass implements QueryBuilder
{
    private string $query = "";
    private string $from = "";
    private string $orderBy = "";
    private string $limit = "";
    private array $selectColumns = [];
    private array $join = [];

    # Return query string
    #[QueryBuilderClass('get')]
    public function get(): string
    {
        $query = $this->query;

        $query = "{$query}" . implode(',', $this->selectColumns);
        $query = "{$query} {$this->from} ";
        $query = !empty($this->join) ? "{$query}" . implode(' ', $this->join) : $query;
        $query = !empty($this->orderBy) ? "{$query} order by {$this->orderBy}" : $query;
        $query = !empty($this->limit) ? "{$query} limit {$this->limit}" : $query;
        self::clean();

        return $query;
    }

    /**
     * Set a limit rows to get in the table
     * @param string $limit
     */
    #[QueryBuilderClass('take')]
    public function take(string $limit=''): void
    {
        $this->limit = $limit;
    }

    /**
     * Set column as the bases of order
     * @param string $table
     * @param string $column
     * @param string $sort
     */
    #[QueryBuilderClass('order')]
    public function order(string $table, string $column, string $sort): void
    {
        $table = rtrim($table, 's');
        $sort = strtoupper($sort);
        $this->orderBy = "{$table}.{$column} $sort";
    }

    /**
     * Accept a singular table name and a column
     * @param  string $table  A table name and must be singular as in without s
     * @param  string $column A column in the given table
     */
    #[QueryBuilderClass('pick')]
    public function pick(string $table, string $column): void
    {
        $table = rtrim($table, 's');

        if (empty($this->query)) {
            $this->query = "SELECT";
        }

        $this->selectColumns[] = " {$table}.{$column} AS {$table}_{$column}";
    }

    /**
     * Create a from string
     * @param  string $table
     */
    #[QueryBuilderClass('from')]
    public function from(string $table=''): void
    {
        $alias = rtrim($table, 's');
        $this->from = "FROM {$table} AS {$alias}";
    }

    /**
     * Create a join query string
     * @param  string $table1
     * @param  string $table2
     * @param  string $condition
     */
    #[QueryBuilderClass('join')]
    public function join(string $table1, string $table2, string $condition): void
    {
        $table1Alias = rtrim($table1, 's');
        $table2Alias = rtrim($table2, 's');
        $condition = explode('=', $condition);

        $this->join[] = "JOIN {$table1} AS {$table1Alias} ON {$table1Alias}.{$condition[0]}=$table2Alias.{$condition[1]}";
    }

    # Clean properties that affects building queries
    #[QueryBuilderClass('clean')]
    private function clean(): void
    {
        $this->query = "";
        $this->from = "";
        $this->orderBy = "";
        $this->limit = "";
        $this->selectColumns = [];
        $this->join = [];
    }
}
