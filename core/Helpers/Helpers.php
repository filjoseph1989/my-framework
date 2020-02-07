<?php

/**
 | Display the lines traverse by php execution
 */
if (! function_exists('trace')) {
    function trace($return = false)
    {
        $e = new \Exception();
        $trace = explode("\n", $e->getTraceAsString());

        $trace = array_reverse($trace);
        array_shift($trace);
        array_pop($trace);

        $result = [];

        for ($i = 0; $i < count($trace); $i++) {
            $result[] = ($i + 1)  . '.' . substr($trace[$i], strpos($trace[$i], ' '));
        }

        $result = array_reverse($result);
        $lines  = "\n\t" . implode("\n\t", $result);

        if ($return === true) {
            return $lines;
        }

        var_dump($lines);
    }
}
