<?php

namespace Core;

class Files
{
    private array $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function name()
    {
        if (isset($this->name)) {
            return $this->name;
        }
        return false;
    }

    public function type()
    {
        if (isset($this->type)) {
            return $this->type;
        }
        return false;
    }

    public function tmp_name()
    {
        if (isset($this->tmp_name)) {
            return $this->tmp_name;
        }
        return false;
    }

    public function error()
    {
        if (isset($this->error)) {
            return $this->error;
        }
        return false;
    }

    public function size()
    {
        if (isset($this->size)) {
            return $this->size;
        }
        return false;
    }

    private function setProperty()
    {
        foreach ($this->file as $key => $value) {
            $this->$key = $value;
        }
    }
}
