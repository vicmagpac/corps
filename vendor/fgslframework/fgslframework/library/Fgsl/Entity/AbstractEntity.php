<?php

namespace Fgsl\Entity;

abstract class AbstractEntity
{
    protected $inputFilter;

    abstract public function getInputFilter();

    public function exchangeArray($array)
    {
        foreach ($array as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

    abstract public function getArrayCopy();
}