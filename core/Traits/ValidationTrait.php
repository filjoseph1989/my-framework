<?php

namespace Core\Traits;

trait ValidationTrait
{
    /**
     * Check if the given array has string keys
     *
     * @param array $array
     * @return boolean
     */
    private function hasStringKeys(array $array) {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }    
}
