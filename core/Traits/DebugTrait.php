<?php

namespace Core\Traits;

/**
 * Task 1:
 *
 * @var mixed
 * @author Fil Beluan
 */
trait DebugTrait
{
    /**
     * Container for available methods
     *
     * @var array
     */
    protected array $availableMethods = [];

    /**
     * Print available methods
     *
     * @return void
     */
    public function availableMethods()
    {
        $class_methods = get_class_methods($this);

        foreach ($class_methods as $key => $value) {
            $reflection = new \ReflectionMethod($this, $value);
            $this->availableMethods[] = $reflection->getFileName() . ':' . $reflection->getStartLine() . " " . $reflection->getName();
        }

        var_dump($this->availableMethods);
        exit;
    }

    /**
     * Dump this model
     *
     * @return void
     */
    public function dd()
    {
        var_dump($this);
        exit;
    }

    /**
     * Dump the array of wheres
     *
     * @return void
     */
    public function dumpWheres()
    {
        var_dump($this->wheres);
        exit;
    }
}
