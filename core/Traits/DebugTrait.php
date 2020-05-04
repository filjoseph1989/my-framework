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

        dump($this->availableMethods);
        exit;
    }

    /**
     * Display available methods of the given object
     *
     * @param  object $object
     * @return void
     */
    public function availableObjectMethods($object)
    {
        $class_methods = get_class_methods($object);

        foreach ($class_methods as $key => $value) {
            $reflection = new \ReflectionMethod($object, $value);
            $this->availableMethods[] = $reflection->getFileName() . ':' . $reflection->getStartLine() . " " . $reflection->getName();
        }

        dump($this->availableMethods);
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
}
