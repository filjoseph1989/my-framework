<?php

namespace Core\Traits;

trait DebugTrait
{
    protected array $availableMethods = [];

    /**
     * Display or print on file the object available methods
     * @param boolean $printOnFile
     */
    public function availableMethods(bool $printOnFile=false): void
    {
        $class_methods = get_class_methods($this);

        foreach ($class_methods as $key => $value) {
            $reflection = new \ReflectionMethod($this, $value);
            $this->availableMethods[] = $reflection->getFileName() . ':' . $reflection->getStartLine() . " " . $reflection->getName();
        }

        if ($printOnFile) {
            self::printOnFile(false);
        } else {
            dump($this->availableMethods);
        }
    }

    /**
     * Display available methods of the given object
     * @param  object $object
     */
    public function availableObjectMethods($object): void
    {
        $class_methods = get_class_methods($object);

        foreach ($class_methods as $key => $value) {
            $reflection = new \ReflectionMethod($object, $value);
            $this->availableMethods[] = $reflection->getFileName() . ':' . $reflection->getStartLine() . " " . $reflection->getName();
        }

        dump($this->availableMethods);
    }

    # Dump this model
    public function dd(): void
    {
        dump($this);
    }

    # dump this class using kint/php
    public function d(): void
    {
        d($this);
    }

    /**
     * Print debug log on file
     * @param mixed   $var
     * @param boolean $append
     */
    public function printOnFile(bool $append = false): void
    {
        if ($append === true) {
            file_put_contents('debug.log', "Log Start: \n". print_r($this, true)."\n\n", FILE_APPEND);
        } else {
            file_put_contents('debug.log', "Log Start: \n". print_r($this, true)."\n\n");
        }
    }
}
