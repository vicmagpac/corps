<?php

namespace Fgsl\Model;

abstract class AbstractModel
{
    protected $inputFilter;

    public function exchangeArray(array $data)
    {
        foreach ($data as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

    abstract public function getInputFilter();

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}