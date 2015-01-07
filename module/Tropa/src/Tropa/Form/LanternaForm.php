<?php

namespace Tropa\Form;

use Fgsl\Form\AbstractForm;

class LanternaForm extends AbstractForm
{
    protected $setorTable;

    public function __construct($name = null)
    {
        parent::__construct('lanterna');
        $this->setAttribute('method', 'post');

        $this->addElement('codigo', 'hidden');
        $this->addElement('nome', 'text', 'Nome');
        $options = array('value_options' => $this->getValueOptions());
        $this->addElement('codigo_setor', 'select', 'Setor', array(), $options);
        $this->addElement('submit', 'submit', 'Gravar');

    }

    private function getSetorTable()
    {
        if (!$this->setorTable) {
            $sm = $GLOBALS['sm'];
            $this->setorTable = $sm->get('Tropa\Model\SetorTable');
        }
        return $this->setorTable;
    }

    private function getValueOptions()
    {
        $valueOptions = array();
        $setores = $this->getSetorTable()->fetchAll();

        foreach ($setores as $setor) {
            $valueOptions[$setor->codigo] = $setor->nome;
        }

        return $valueOptions;
    }
}