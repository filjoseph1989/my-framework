<?php

namespace Core\Contracts;

/**
 * A contract for validation
 */
interface ValidationInterface
{
    /**
     * Check if has errors
     *
     * @return boolean
     */
    public function hasErrors();

    /**
     * Return the array of errors
     *
     * @return array
     */
    public function errors();

    /**
     * Put each error in container array
     *
     * @param string $input
     * @param array $value
     * @return void
     */
    public function appendError(string $input, array $value = []);
}
