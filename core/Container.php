<?php

namespace Core;

/**
 * @author Fil Beluan
 */
class Container
{
    protected array $items = [];
    protected array $cache = [];

    /**
     * Instantiate
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            $this->setItem($key, $value);
        }
    }

    /**
     * Set items
     *
     * @param string $key
     * @param string $value
     */
    public function setItem($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * Return item
     *
     * @param  mixed $key
     * @return mixed
     */
    public function getItem($key)
    {
        if (!isset($this->items[$key])) {
            return null;
        }

        if ( isset($this->cache[$key]) ) {
            return $this->cache[$key];
        }

        # Call the closure store in items[]
        $item = call_user_func($this->items[$key], $this);

        $this->cache[$key] = $item;

        return $item;
    }

    /**
     * Remove item
     *
     * @param string $$key
     * @return void
     */
    public function unsetItem($key)
    {
        if (isset($this->items[$key])) {
            unset($this->items[$key]);
        }
    }

    /**
     * Magically call the property
     *
     * @param  mixed $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->getItem($property);
    }
}
