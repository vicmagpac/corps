<?php

namespace Tropa\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class Lanterna
{
    public $codigo;
    public $nome;
    /**
     * @var \Tropa\Model\Setor
     */
    public $setor;

    protected $inputFilter;

    public function exchangeArray($data)
    {
        $this->codigo           = (isset($data['codigo'])) ? $data['codigo'] : null;
        $this->nome             = (isset($data['nome'])) ? $data['nome'] : null;
        $this->setor            = new Setor();
        $this->setor->codigo    = (isset($data['codigo_setor'])) ? $data['codigo_setor'] : null;
        $this->setor->nome      = (isset($data['setor'])) ? $data['setor'] : null;
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'nome',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding'  => 'UTF-8',
                            'min'       => 2,
                            'max'       => 30
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name'      => 'codigo_setor',
                'required'  => true,
                'filters'   => array(
                    array('name' => 'Int')
                ),
                'validators' => array(
                    array(
                        'name' => 'Digits'
                    )
                )
            )));
            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function getArrayCopy()
    {
        return array(
            'codigo' => $this->codigo,
            'nome'   => $this->nome,
            'codigo_setor' => $this->setor->codigo
        );
    }

    public function toArray()
    {
        return get_object_vars($this);
    }
}