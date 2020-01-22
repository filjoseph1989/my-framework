<?php

namespace Core\Model;

use Core\Model\Database;
use Core\Model\ObjectMappingInterface;

class ObjectMapping implements ObjectMappingInterface
{
    public object $model;

    protected string $ClassName = '';
    protected string $PrimaryKey = '';
    protected array  $ForeignKeys = [];

    public function __construct($object)
    {
        $this->model = $object;

        $database = new Database();
        $classPathArray    = explode("\\", get_class($object));
        $this->ClassName   = $classPathArray[count($classPathArray) - 1];
        $this->PrimaryKey  = $database->getPrimaryKey(strtolower($this->ClassName));
        $this->ForeignKeys = $database->getForeignKey(strtolower($this->ClassName));
    }

    /**
     * Mapping database result
     *
     * @param  object $obj
     * @param  array  $rows
     * @return void
     */
    public function map(&$model, $items)
    {
        $relations = self::relation($items);

        foreach ($items[0] as $key => $value) {
            if (isset($relations[$key])) {
                $linkedClassName = self::className($key);

                if (class_exists($linkedClassName)) {
                    $objectToPush = new $linkedClassName();
                    $objectToPush = $objectToPush->find($relations[$key]);
                    unset($relations[$key]);
                    $relations[$linkedClassName] = $objectToPush;
                }
            }
        }

        $model->set('relations', $relations);
    }

    /**
     * Should return an object map
     *
     * @param  int $id Table ID
     * @return object
     */
    public function find(string $id)
    {
        $database = new Database();

        if ($database->connect()) {
            $items = $database
                ->select(strtolower($this->ClassName), ['*'])
                ->whereArray([
                    "column"    => $this->PrimaryKey,
                    "value"     => $id,
                    "condition" => "=",
                ])
                ->get();

            $this->model->set('attributes', $items[0]);

            self::map($this->model, $items);

            return $this->model;
        }
    }

    /**
     * Return class name
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
    private function relation(array $items)
    {
        $relations = [];
        foreach ($this->ForeignKeys as $key => $value) {
            $relations[$value] = $items[0][$value];
        }

        return $relations;
    }
}
