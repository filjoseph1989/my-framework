<?php

namespace Core\Model;

use Core\Contracts\ObjectMappingInterface;
use Core\Model\Database;
use Core\Traits\ObjectMappingTrait;

/**
 * Map database as object
 *
 * @author Fil Beluan
 */
class ObjectMapping implements ObjectMappingInterface
{
    // Issue 43
    use ObjectMappingTrait;

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
    private function relation(object $rows)
    {
        $relations = [];

        foreach ($this->foreignKeys as $key => $foreignKey) {
            $relations[$foreignKey] = $rows->$foreignKey;
        }

        return $relations;
    }
}
