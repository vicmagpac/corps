<?php

namespace Tropa\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class Setor
{
    public $codigo;
    public $nome;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->codigo   = (isset($data['codigo'])) ? $data['codigo'] : null;
        $this->nome     = (isset($data['nome'])) ? $data['nome'] : null;
    }

    public function getInputFIlter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'      => 'codigo',
                'required'  => false,
                'filters'   => array(
                    array('name' => 'Int')
                ),
                'validators' => array(
                    array(
                        'name'      => 'Between',
                        'options'   => array(
                            'min' => 0,
                            'max' => 3600
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name'      => 'nome',
                'required'  => true,
                'filters'   => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name'      => 'StringLength',
                        'options'   => array(
                            'encoding'  => 'UTF-8',
                            'min'       => 2,
                            'max'       => 30
                        )
                    )
                )
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}