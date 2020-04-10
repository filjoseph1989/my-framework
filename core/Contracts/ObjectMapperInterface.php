<?php

namespace Core\Contracts;

/**
 * A contract tha protect ObjectMapper
 */
interface ObjectMapperInterface
{
    /**
     * Initiate object mapping
     *
     * @param object $object The model object
     */
    public function __construct($model);

    /**
     * Mapping database result
     *
     * @param  object $model
     * @param  array  $columns
     * @return void
     */
    public function map(&$model, $row);
}