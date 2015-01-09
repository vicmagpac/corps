<?php

namespace Tropa\Model;

use Fgsl\InputFilter\InputFilter;
use Fgsl\Model\AbstractModel;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Validator\Between;
use Zend\Validator\StringLength;

class Setor extends AbstractModel
{
    protected $inputFilter;

    public function getInputFIlter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $inputFilter->addFilter('codigo', new Int());
            $inputFilter->addValidator('codigo', new Between(array(
                'min' => 0,
                'max' => 3600
            )));

            $inputFilter->addFilter('nome', new StripTags());
            $inputFilter->addFilter('nome', new StringTrim());
            $inputFilter->addValidator('nome', new StringLength(array(
                'encoding' => 'UTF-8',
                'min'      => 2,
                'max'      => 30
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}