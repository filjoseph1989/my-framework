<?php

namespace Core\Traits;

trait LogTrait
{
    /**
     * Add to log file
     * @param  string $message
     */
    #[LogTrait('addToLog')]
    public function addToLog(string $message): bool
    {
        debug_print_append("\n{$message}\n");
        debug_print_append(trace(true));
        return false;
    }
}
