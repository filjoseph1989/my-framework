<?php

namespace Core\Mapper;

use Core\Contracts\ObjectMapperInterface;
use Core\Mapper\Traits\ObjectMapperTrait;
use Core\Model\Database;

class ObjectMapper implements ObjectMapperInterface
{
    use ObjectMapperTrait;

    public object $model;
    protected string $table = '';
    protected string $primaryKey = '';
    protected object $database;
    protected array $foreignKeys = [];
    protected array $rows = [];

    /**
     * Initiate object mapping
     * @param object $object The model object
     */
    public function __construct(object $model)
    {
        $this->model = $model;

        if (!is_null($this->model->table)) {
            $this->database    = new Database();
            $this->table       = $this->model->table;
            $this->primaryKey  = $this->database->getPrimaryKey(strtolower($this->table));
            $this->foreignKeys = $this->database->getForeignKey(strtolower($this->table));
        }
    }

    /**
     * Mapping database result
     * @param  object $model
     * @param  array  $columns
     */
    public function map(object &$model, object $row): object
    {
        $hasOne = self::hasOneRelation($row);

        if (!is_null($model)) {
            $belongsTo = self::hasBelongsToRelation($model);
        }

        foreach ($row as $key => $value) {
            if (isset($hasOne[$key]) && !empty($hasOne[$key])) {
                $relatedModel = $model->hasOne[$key] ?? '';
                if (empty($relatedModel)) continue;

                if (class_exists($relatedModel)) {
                    $property       = str_replace('_id', '', $key);
                    $row->$property = new $relatedModel();
                    $row->$property->find($value);
                }

                unset($hasOne[$key]);
            }

            if (isset($belongsTo[$key]) && !empty($belongsTo[$key])) {
                if (class_exists($model->belongsTo[$key])) {
                    $property       = str_replace('_id', '', $key);
                    $row->$property = new $model->belongsTo[$key]();
                    $row->$property->find($value);
                }
            }
        }

        return $row;
    }

    // Return the connected to database object
    public function getDatabaseConnection(): object
    {
        return $this->database;
    }

    /**
     * Collect relation IDs and return
     * @param  array  $rows
     */
    private function hasOneRelation(object &$rows): array
    {
        foreach ($this->foreignKeys as $key => $foreignKey) {
            $relations[$foreignKey] = $rows->$foreignKey;
        }

        return $relations ?? [];
    }

    private function hasBelongsToRelation(object &$model): array
    {
        if (property_exists($model, 'belongsTo')) {
            foreach ($model->belongsTo as $key => $value) {
                $relations[$key] = $value;
            }
        }

        return $relations ?? [];
    }
}
