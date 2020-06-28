<?php

namespace Core\Mapper\Classes;

use Core\Mapper\Traits\ObjectMapperParserTrait;

class PrepareData
{
    use ObjectMapperParserTrait;

    private array $data;
    private object $database;
    private string $query;
    private string $table;
    private int $count;
    private int $int;

    public function __construct(object $database, string $table, array $data=[])
    {
        $this->data = $data;
        $this->database = $database;
        $this->table = $table;
    }

    /**
     * Issue 87
     * @return
     */
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

    /**
     * Return the inserted ID
     *
     * @return int
     */
    public function insertedId()
    {
        return $this->id;
    }
}
