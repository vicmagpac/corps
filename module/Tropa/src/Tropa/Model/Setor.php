<?php

namespace Tropa\Model;

use Doctrine\ORM\Mapping as ORM;
use Fgsl\InputFilter\InputFilter;
use Fgsl\Entity\AbstractEntity;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Validator\Between;
use Zend\Validator\StringLength;

/**
 * @ORM\Entity
 * @ORM\Table(name="setor")
 */
class Setor extends AbstractEntity
{
    /**
     * @ORM\Id @ORM\Column(type="integer")
     */
    public $codigo;

    /**
     * @ORM\Column(type="string")
     */
    public $nome;

    protected $inputFilter;

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

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