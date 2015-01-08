<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tropa\Controller;

use Fgsl\Mvc\Controller\AbstractCrudController;

class SetorController extends AbstractCrudController
{
    public function __construct()
    {
        $this->formClass = 'Tropa\Form\SetorForm';
        $this->modelClass = 'Tropa\Model\Setor';
        $this->namespaceTableGateway = 'Tropa\Model\SetorTable';
        $this->route = 'setor';
        $this->title = 'Cadastro de setores espaciais';
        $this->label['yes']  = 'Sim';
        $this->label['no']   = 'Não';
        $this->label['add']  = 'Incluir';
        $this->label['edit'] = 'Alterar';
    }

    public function indexAction()
    {
        $viewModel = parent::indexAction();
        $urlHomepage = $this->url()->fromRoute('application', array('controller' => 'index', 'action' => 'menu'));
        $viewModel->setVariable('urlHomepage', $urlHomepage);
        return $viewModel;
    }
}
