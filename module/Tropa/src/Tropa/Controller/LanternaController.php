<?php

namespace Tropa\Controller;

use Tropa\Form\LanternaForm;
use Tropa\Model\Lanterna;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class LanternaController extends AbstractActionController
{
    protected $lanternaTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'lanternas' => $this->getLanternaTable()->fetchAll()
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

        return array('form' => $form);
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
            'form'   => $form
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
            'form'   => $form->getDeleteForm($codigo)
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
}