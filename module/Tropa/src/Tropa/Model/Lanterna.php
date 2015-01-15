<?php

namespace Tropa\Model;

use Doctrine\ORM\Mapping as ORM;
use Fgsl\Entity\AbstractEntity;
use Fgsl\InputFilter\InputFilter;
use Zend\Filter\Int;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Validator\StringLength;
use Zend\Validator\Digits;

/**
 * @ORM\Entity
 * @ORM\Table(name="lanterna")
 */
class Lanterna extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $codigo;

    /**
     * @ORM\Column(type="string")
     */
    public $nome;

    /**
     * @ORM\OneToOne(targetEntity="Setor")
     * @ORM\JoinColumn(name="codigo_Setor", referencedColumnName="codigo")
     */
    public $setor;

    protected $inputFilter;

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getSetor()
    {
        return $this->setor;
    }

    public function setSetor($setor)
    {
        $this->setor = $setor;
    }

    public function exchangeArray($data)
    {
        if (is_array($data)) {
            $this->codigo   = $data['codigo'];
            $this->nome     = $data['nome'];

            $em = $GLOBALS['entityManager'];
            $this->setor = $em->getRepository('Tropa\Model\Setor')->find($data['codigo_setor']);
        } else {
            $this->codigo   = $data->codigo;
            $this->nome     = $data->nome;
            $this->setor    = $data->setor;
        }
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
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