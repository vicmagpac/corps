<?php

namespace Tropa\Controller;

use Fgsl\Mvc\Controller\AbstractCrudController;

class LanternaController extends AbstractCrudController
{
    public function __construct()
    {
        $this->formClass = 'Tropa\Form\LanternaForm';
        $this->modelClass = 'Tropa\Model\Lanterna';
        $this->namespaceTableGateway = 'Tropa\Model\LanternaTable';
        $this->route = 'lanterna';
        $this->title = 'Cadastro de lanternas verdes';
        $this->label['yes']  = 'Sim';
        $this->label['no']   = 'Não';
        $this->label['add']  = 'Incluir';
        $this->label['edit'] = 'Alterar';
    }
}