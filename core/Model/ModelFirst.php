<?php

namespace Core\Model;

use Core\Iterators\ModelRowIterator;

/**
 * Evaluate model to first
 *
 * @author Fil <filjoseph22@gmail.com>
 */
class ModelFirst
{
    private object $model;

    /**
     * Accept Model
     *
     * @param Core\Model $model
     */
    public function __construct($model)
    {
        if (!is_null($model)) {
            $this->model = $model;
        }
    }

    /**
     * Return first row
     *
     * @return object
     */
    public function first()
    {
        if (!isset($this->model->rows[0])) {
            return null;
        }

        return $this->model->rows[0];
    }
}
