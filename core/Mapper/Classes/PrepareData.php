<?php

namespace Core\Mapper\Classes;

use Core\Mapper\Traits\ObjectMapperParserTrait;

class PrepareData
{
    use ObjectMapperParserTrait;

    private array $data;
    private object $database;
    private object $model;
    private int $count;
    private int $int;
    private string $table;
    private string $query;
    private string $wherecondition = "";
    private string $updateCondition = "";
    private string $order = "";
    private string $limit = "";
    private string $offset = ""; # skip

    public function __construct(
        object &$model,
        object &$database,
        string $table,
        array $data=[]
    ) {
        $this->data     = $data;
        $this->database = $database;
        $this->table    = $table;
        $this->model    = $model;
    }

    /**
     * Accept string as sql condition
     * @param string $condition
     */
    public function setCondition(string $condition): void
    {
        $this->whereCondition = $condition;
    }

    // Return query where condition string
    #[PrepareData('getWhereCondition')]
    public function getWhereCondition(): string
    {
        return $this->whereCondition;
    }

    // Return query update data string
    #[PrepareData('getUpdateCondition')]
    public function getUpdateCondition(): string
    {
        return $this->updateCondition;
    }

    // Return query string
    #[PrepareData('getQueryString')]
    public function getQueryString(): string
    {
        return $this->query;
    }

    // Prepare select query
    #[PrepareData('select')]
    public function select(): void
    {
        $this->query = "SELECT * FROM {$this->table}";

        if (!empty($this->whereCondition)) {
            $this->query = "{$this->query} WHERE {$this->whereCondition}";
        }

        if (!empty($this->order)) {
            $this->query = "{$this->query} {$this->order}";
        }

        if (!empty($this->limit)) {
            $this->query = "{$this->query} {$this->limit}";
        }

        if (!empty($this->offset)) {
            $this->query = "{$this->query} {$this->offset}";
        }
    }

    // Prepare skip query
    #[PrepareData('skip')]
    public function skip(): void
    {
        if ($this->model->limit > 0) {
            $this->offset = "OFFSET {$this->model->skip}";
        }
    }

    // Prepare limit string
    #[PrepareData('limit')]
    public function limit(): void
    {
        if ($this->model->limit > 0) {
            $this->limit = "LIMIT {$this->model->limit}";
        }
    }

    // Prepare order by string
    #[PrepareData('orderBy')]
    public function order(): void
    {
        if (strlen($this->model->orderBy) > 0) {
            $this->order = "ORDER BY {$this->model->orderBy}";
        }
    }

    // Building the where condition
    #[PrepareData('where')]
    public function where(): void
    {
        $whereQuery = [];
        foreach ($this->model->wheres as $key => $value) {
            $value        = $this->database->scape($value);
            $whereQuery[] = "$key='$value'";
        }

        $this->whereCondition = implode(' AND ', $whereQuery);
    }

    // Prepare data
    #[PrepareData('prepareData')]
    public function updateData()
    {
        $values = [];

        foreach ($this->data as $key => $value) {
            $value = $this->database->scape($value);
            $values[] = "{$key}='{$value}'";
        }

        $this->updateCondition = implode(',', $values);
    }

    /**
     * Prepare update query
     * @param  string $updateData
     */
    public function update()
    {
        $this->query = "UPDATE {$this->table} SET {$this->updateCondition}";

        if ($this->whereCondition != "") {
            $this->query = "{$this->query} WHERE {$this->whereCondition}";
        }
    }

    # Issue 87
    #[PrepareData('create')]
    public function create()
    {
        if ($this->database->isConnected()) {
            if (!isset($this->data['created_at'])) {
                $this->data['created_at'] = date('Y-m-d H:i:s');
            }

            if (!isset($this->data['updated_at'])) {
                $this->data['updated_at'] = date('Y-m-d H:i:s');
            }

            $newData     = self::prepareInsertData($this->data);
            $this->query = "INSERT INTO {$this->table} ({$newData['keys']}) VALUES ({$newData['values']})";
            $results     = $this->database->query($this->query);

            if (!$results) {
                return false;
            }

            $this->count = $this->database->count();

            if ($this->count <= 0) {
                return false;
            }

            $this->id = $this->database->insertId();
            if ($this->count > 0) {
                return true;
            }
        }
    }

    // Return the inserted ID
    #[PrepareData('insertedId')]
    public function insertedId(): int
    {
        return $this->id;
    }
}
