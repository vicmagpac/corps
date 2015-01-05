<?php

namespace Tropa\Controller;

use Tropa\Form\LanternaForm;
use Tropa\Model\Lanterna;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class LanternaController extends AbstractActionController
{
    protected $lanternaTable;

    public function indexAction()
    {
        $partialLoop = $this->getSm()->get('viewhelpermanager')->get('PartialLoop');
        $partialLoop->setObjectKey('lanterna');

        $urlAdd = $this->url()->fromRoute('lanterna', array('action' => 'add'));
        $urlEdit = $this->url()->fromRoute('lanterna', array('action' => 'edit'));
        $urlDelete = $this->url()->fromRoute('lanterna', array('action' => 'delete'));
        $urlHomepage = $this->url()->fromRoute('home');

        $placeholder = $this->getSm()->get('viewhelpermanager')->get('Placeholder');
        $placeholder('url')->edit = $urlEdit;
        $placeholder('url')->delete = $urlDelete;

        $pageAdapter = new DbSelect($this->getLanternaTable()->getSelect(), $this->getLanternaTable()->getSql());
        $paginator = new Paginator($pageAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page', 1));
        $paginator->setItemCountPerPage(2);

        return new ViewModel(array(
            'paginator' => $paginator,
            'title'     => $this->setAndGetTitle(),
            'urlAdd'    => $urlAdd,
            'urlHomepage' => $urlHomepage
        ));
    }

    public function addAction()
    {
        $form = new LanternaForm();
        $form->get('submit')->setValue('Cadastrar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $lanterna = new Lanterna();
            $form->setInputFilter($lanterna->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $lanterna->exchangeArray($form->getData());
                $this->getLanternaTable()->saveLanterna($lanterna);

                return $this->redirect()->toRoute('lanterna');
            }
        }

        return array(
            'form' => $form,
            'title' => $this->setAndGetTitle()
        );
    }

    public function editAction()
    {
        $codigo = (int) $this->params()->fromRoute('codigo', null);
        if (is_null($codigo)) {
            $this->redirect()->toRoute('lanterna');
        }

        $lanterna = $this->getLanternaTable()->getLanterna($codigo);

        $form = new LanternaForm();
        $form->bind($lanterna);
        $form->get('submit')->setValue('Editar');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($lanterna->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getLanternaTable()->saveLanterna($form->getData());

                return $this->redirect()->toRoute('lanterna');
            }
        }

        return array(
            'codigo' => $codigo,
            'form'   => $form,
            'title' => $this->setAndGetTitle()
        );
    }

    public function deleteAction()
    {
        $codigo = (int) $this->params()->fromRoute('codigo', null);
        if (is_null($codigo)) {
            $this->redirect()->toRoute('lanterna');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Nao');

            if ($del == 'Sim') {
                $this->getLanternaTable()->deleteLanterna($codigo);
            }

            return $this->redirect()->toRoute('lanterna');
        }

        $form = new LanternaForm();

        return array(
            'codigo' => $codigo,
            'form'   => $form->getDeleteForm($codigo),
            'title' => $this->setAndGetTitle()
        );
    }

    /**
     * @return \Tropa\Model\LanternaTable
     */
    public function getLanternaTable()
    {
        if (!$this->lanternaTable) {
            $sm = $this->getServiceLocator();
            $this->lanternaTable = $sm->get('Tropa\Model\LanternaTable');
        }

        return $this->lanternaTable;
    }

    public function getSm()
    {
        return $this->getEvent()->getApplication()->getServiceManager();
    }

    public function setAndGetTitle()
    {
        $title = 'Lanternas verdes';
        $headTitle = $this->getSm()->get('viewhelpermanager')->get('HeadTitle');
        $headTitle($title);
        return $title;
    }
}