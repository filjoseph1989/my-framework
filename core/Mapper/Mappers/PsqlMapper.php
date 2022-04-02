<?php

namespace Core\Mapper\Mappers;

use Core\Contracts\ObjectMapperInterface;
use Core\Iterators\ModelIterator;
use Core\Mapper\Classes\QueryBuilder;
use Core\Mapper\Traits\ObjectMapperTrait;
use Core\Model\Traits\ModelTrait;
use Core\Model\ModelData;
use Core\Model\Database;
use Core\Traits\ValidationTrait;

// class PsqlMapper implements ObjectMapperInterface
// Todo-2
class PsqlMapper
{
    use ModelTrait;
    use ValidationTrait;

    private object $model;
    private object $builder;

    /**
     * Initiate object mapping
     * @param object $object The model object
     */
    public function __construct(object $model)
    {
        $this->model = $model;

        if (!is_null($this->model->table)) {
            $this->database = $this->model->database;
            $this->table    = $this->model->table;
            $this->builder = new QueryBuilder($this);
        }
    }

    /**
     * Magically call the property
     * @param  mixed $property
     */
    public function __get($property): mixed
    {
        return $this->getProperty($property);
    }

    /**
     * Return model data
     *
     * @return object
     */
    public function get(): object
    {
        $data = $this->builder->select();
        if (is_array($data) && $this->hasStringKeys($data)) {
            $model = $this->model;
            $newModelData = new ModelData();

            foreach ($data as $key => $value) {
                $newModelData->setProperty($key, $value);
            }

            $model->addCollections($newModelData);
            return new ModelIterator($model);
        }
    }
}
