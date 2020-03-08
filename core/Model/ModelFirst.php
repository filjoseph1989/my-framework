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
    private object $model;

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
        return $this->model->rows[0];
    }
}
