<?php

namespace Core\Mapper;

use Core\Contracts\ObjectMapperInterface;
<<<<<<< HEAD:core/Mapper/ObjectMapper.php
use Core\Mapper\Traits\ObjectMapperTrait;
use Core\Model\Database;
=======
use Core\Model\Database;
use Core\Traits\ObjectMapperTrait;
>>>>>>> 9287905... Core: Rename objectMapping to objectMapper:core/Model/ObjectMapper.php

/**
 * Map database as object
 *
 * @author Fil Beluan
 */
class ObjectMapper implements ObjectMapperInterface
{
    // Issue 43
    use ObjectMapperTrait;

    public object $model;

    protected string $table      = '';
    protected string $primaryKey  = '';
    protected array $foreignKeys = [];
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
    public function __construct($model)
    {
        $this->model = $model;
        $this->table = $this->model->table;

        $this->database    = new Database();
        $this->primaryKey  = $this->database->getPrimaryKey(strtolower($this->table));
        $this->foreignKeys = $this->database->getForeignKey(strtolower($this->table));
    }

    /**
     * Mapping database result
     *
     * @param  object $model
     * @param  array  $columns
     * @return void
     */
    public function map(&$model, $row)
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
     * Assign ID values to the foriegn keys
     *
     * @param  array  $items
     * @return array
     */
    private function relation(object &$rows)
    {
        $relations = [];

        foreach ($this->foreignKeys as $key => $foreignKey) {
            $relations[$foreignKey] = $rows->$foreignKey;
        }

        return $relations;
    }
}
