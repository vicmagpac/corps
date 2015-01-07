<?php

namespace Tropa\Form;

use Fgsl\Form\AbstractForm;

class SetorForm extends AbstractForm
{
    public function __construct($name = null)
    {
        parent::__construct('setor');
        $this->setAttribute('method', 'post');

        $this->addElement('codigo', 'text', 'Código');
        $this->addElement('nome', 'text', 'Nome');
        $this->addElement('submit', 'submit', 'Gravar');
    }


}