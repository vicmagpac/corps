<?php

namespace Tropa\Controller;

use Fgsl\Mvc\Controller\AbstractDoctrineCrudController;

class LanternaController extends AbstractDoctrineCrudController
{
    public function __construct()
    {
        $this->formClass = 'Tropa\Form\LanternaForm';
        $this->modelClass = 'Tropa\Model\Lanterna';
        $this->namespaceTableGateway = 'Tropa\Model\LanternaTable';
        $this->route = 'lanterna';
        $this->title = 'Cadastro de lanternas verdes';
        $this->label['yes'] = 'Sim';
        $this->label['no'] = 'Não';
        $this->label['add'] = 'Incluir';
        $this->label['edit'] = 'Alterar';
    }

    public function indexAction()
    {
        $viewModel = parent::indexAction();
        $urlHomepage = $this->url()->fromRoute('application', array(
            'controller' => 'index',
            'action'     => 'menu')
        );

        $viewModel->setVariable('urlHomepage', $urlHomepage);

        // sem essa consulta o proxy não retornará o relacionamento
        $em = $GLOBALS['entityManager'];
        $em->getRepository('Tropa\Model\Setor')->findAll();

        return $viewModel;
    }
}