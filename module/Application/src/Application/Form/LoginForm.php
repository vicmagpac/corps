<?php

namespace Application\Form;

use Zend\Form\Element\Submit;
use Zend\Form\Element\Password;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = 'login_form')
    {
        parent::__construct($name);
        $this->setAttribute('method', 'post');

        $element = new Text('identidade');
        $element->setLabel('Usuário');
        $this->add($element);

        $element = new Password('credencial');
        $element->setLabel('Senha');
        $this->add($element);

        $element = new Submit('login');
        $element->setValue('Entrar');
        $this->add($element);
    }
}