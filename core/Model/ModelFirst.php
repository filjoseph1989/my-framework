<?php

namespace Core\Model;

use Core\Model\ModelRowIterator;

/**
 * Evaluate model to first
 *
 * @author Fil <filjoseph22@gmail.com>
 */
class ModelFirst
{
    private array $model;

    /**
     * Accept Model
     *
     * @param Core\Model $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Return first row
     *
     * @return object
     */
    public function first()
    {
        foreach ($this->model as $key => $model) {
            return self::evaluateModel($model);
        }
    }

    /**
     * Return first model found
     *
     * @param  object $model
     * @return Core\Model
     */
    private function evaluateModel(object $model)
    {
        foreach (new ModelRowIterator($model) as $key => $value) {
            return $value;
        }
    }
}
