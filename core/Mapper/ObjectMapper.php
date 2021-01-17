<?php

namespace Core\Mapper;

use Core\Contracts\ObjectMapperInterface;
use Core\Mapper\Traits\ObjectMapperTrait;
use Core\Model\Database;

/**
 * Map database as object
 *
 * @author Fil Beluan
 */
class ObjectMapper implements ObjectMapperInterface
{
    use ObjectMapperTrait;

    /**
     * The model to map with
     * @var object
     */
    public object $model;

    /**
     * The model table name
     * @var string
     */
    protected string $table      = '';

    /**
     * Model primary key
     * @var string
     */
    protected string $primaryKey = '';

    /**
     * Collection of model foreign keys
     * @var array
     */
    protected array $foreignKeys = [];

    /**
     * Database object container
     * @var object
     */
    protected object $database;

    /**
     * Rows container
     * @var array
     */
    protected array $rows = [];

    /**
     * Initiate object mapping
     *
     * @param object $object The model object
     */
    public function __construct(object $model)
    {
        $this->model = $model;
        $this->table = $this->model->table;

        $this->database    = new Database();
        $this->primaryKey  = $this->database->getPrimaryKey(strtolower($this->table));
        $this->foreignKeys = $this->database->getForeignKey(strtolower($this->table));
    }

    /**
     * Mapping database result
     * @param  object $model
     * @param  array  $columns
     */
    public function map(object &$model, object $row): object
    {
        $relations = self::relation($row);

        foreach ($row as $key => $value) {
            if (isset($relations[$key]) && !empty($relations[$key])) {
                $linkedClassName = $model->relations[$key];

                if (class_exists($linkedClassName)) {
                    unset($relations[$key]);
                    $key       = str_replace('_id', '', $key);
                    $row->$key = new $linkedClassName();
                    $row->$key->find($value); # Issue 66
                }
            }
        }

        return $row;
    }

    /**
     * Return the connected to database object
     * @return object
     */
    public function getDatabaseConnection()
    {
        return $this->database;
    }

    /**
     * Collect relation IDs and return
     * @param  array  $rows
     */
    private function relation(object &$rows): array
    {
        $relations = [];

        foreach ($this->foreignKeys as $key => $foreignKey) {
            $relations[$foreignKey] = $rows->$foreignKey;
        }

        return $relations;
    }
}
