<?php

namespace Tropa\Model;

use Fgsl\Model\AbstractModel;
use Fgsl\InputFilter\InputFilter;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Validator\StringLength;
use Zend\Validator\Digits;

class Lanterna extends AbstractModel
{
    public $codigo;
    public $nome;
    /**
     * @var \Tropa\Model\Setor
     */
    public $setor;

    protected $inputFilter;

    public function exchangeArray(array $data)
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
            $inputFilter->addFilter('nome', new StripTags());
            $inputFilter->addFilter('nome', new StringTrim());
            $inputFilter->addValidator('nome', new StringLength(array(
                'encoding'  => 'UTF-8',
                'min'       => 2,
                'max'       => 30
            )));

            $inputFilter->addFilter('codigo_setor', new Int());
            $inputFilter->addValidator('codigo_setor', new Digits());

            $inputFilter->addChains();

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }


}