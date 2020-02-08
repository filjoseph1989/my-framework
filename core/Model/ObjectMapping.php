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
    public function map(&$model, $rows)
    {
        $relations = self::relation($rows);

        foreach ($rows[0] as $key => $value) {
            if (isset($relations[$key])) {
                $linkedClassName = self::className($key);

                if (class_exists($linkedClassName)) {
                    $objectToPush = new $linkedClassName();

                    if (!empty($relations[$key])) {
                        $objectToPush = $objectToPush->find($relations[$key]);
                        unset($relations[$key]);
                        $relations[$linkedClassName] = $objectToPush;
                    }
                }
            }
        }

        $model->set('relations', $relations);
    }

    /**
     * Return class name
     *
     * Task 23: Dapat naka define na daan sa model ang class na gamitun sa relationship
     *  sa model
     *
     * @param  string $key
     * @return atring
     */
    private function className(string $key)
    {
        $linkedClassName = str_replace("_id", "s", $key);
        $linkedClassName = str_replace("_", " ", $linkedClassName);
        $linkedClassName = ucwords($linkedClassName);

        return str_replace(" ", "", $linkedClassName);
    }

    /**
     * Assign ID values to the foriegn keys
     *
     * @param  array  $items
     * @return array
     */
    private function relation(array $rows)
    {
        $relations = [];

        foreach ($this->foreignKeys as $key => $foreignKey) {
            $relations[$foreignKey] = $rows[0][$foreignKey];
        }

        return $relations;
    }
}
