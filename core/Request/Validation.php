<?php

namespace Core\Request;

use Core\Contracts\ValidationInterface;

/**
 * Validation class
 *
 * @author Fil <filjoseph22@gmail.com>
 */
class Validation implements ValidationInterface
{
    /**
     * Containers of errors
     */
    private array $errors = [];

    /**
     * Check if has errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        if (count($this->errors) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Return the array of errors
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Put each error in container array
     *
     * @param string $input
     * @param array $value
     * @return void
     */
    public function appendError(string $input, array $value = [])
    {
        $this->errors[$input] = $value;
    }
}
