<?php

namespace Tropa\Form;

use Zend\Form\Form;

class SetorForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('setor');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name'          => 'codigo',
            'attributes'    => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Código'
            )
        ));

        $this->add(array(
            'name'          => 'nome',
            'attributes'    => array(
                'type' => 'text'
            ),
            'options' => array(
                'label' => 'Nome'
            )
        ));

        $this->add(array(
           'name'           => 'submit',
            'attributes'    => array(
                'type'  => 'submit',
                'value' => 'Gravar',
                'id'    => 'submitbutton'
            )
        ));
    }

    public function getDeleteForm($codigo)
    {
        $this->remove('codigo');
        $this->remove('nome');
        $this->remove('submit');

        $this->add(array(
            'name' => 'del',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Sim',
                'id'    => 'del'
            )
        ));

        $this->add(array(
            'name' => 'return',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Não',
                'id'    => 'return'
            )
        ));

        return $this;
    }
}