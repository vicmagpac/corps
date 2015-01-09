<?php

namespace Fgsl\Model;
use Zend\Db\RowGateway\RowGateway;

abstract class AbstractModel extends RowGateway
{
    protected $inputFilter;

    abstract public function getInputFilter();

    public function getArrayCopy()
    {
        return $this->data;
    }
}