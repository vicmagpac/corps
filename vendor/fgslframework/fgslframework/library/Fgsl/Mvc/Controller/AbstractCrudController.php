<?php

namespace Fgsl\Mvc\Controller;

use Fgsl\Model\AbstractModel;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

abstract class AbstractCrudController extends AbstractActionController
{
    protected $formClass;
    protected $modelClass;
    protected $namespaceTableGateway;
    protected $route;
    protected $tableGateway;
    protected $title;
    protected $label = array(
        'add'   => 'Add',
        'edit'  => 'Edit',
        'yes'   => 'Yes',
        'no'    => 'No'
    );

    public function indexAction()
    {
        $partialLoop = $this->getSm()->get('viewhelpermanager')->get('PartialLoop');
        $partialLoop->setObjectKey('model');

        $urlAdd = $this->url()->fromRoute($this->route, array('action' => 'add'));

        $urlEdit = $this->url()->fromRoute($this->route, array('action' => 'edit'));
        $urlDelete = $this->url()->fromRoute($this->route, array('action' => 'delete'));
        $urlHomepage = $this->url()->fromRoute('home');

        $placeHolder = $this->getSm()->get('viewhelpermanager')->get('Placeholder');
        $placeHolder('url')->edit = $urlEdit;
        $placeHolder('url')->delete = $urlDelete;

        $pageAdapter = new DbSelect($this->getTableGateway()->getSelect(), $this->getTableGateway()->getSql());
        $paginator = new Paginator($pageAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page', 1));

        return new ViewModel(array(
            'paginator' => $paginator,
            'title'     => $this->setAndGetTitle(),
            'urlAdd'    => $urlAdd,
            'urlHomepage' => $urlHomepage
        ));
    }

    public function addAction()
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass();

        $formClass = $this->formClass;
        $form = new $formClass();

        $form->get('submit')->setValue($this->label['add']);
        $form->bind($model);

        $urlAction = $this->url()->fromRoute($this->route, array('action' => 'add'));

        return $this->save($model, $form, $urlAction, null);
    }

    public function editAction()
    {
        $key = (int) $this->params()->fromRoute('key', null);
        if (is_null($key)) {
            return $this->redirect()->toRoute($this->route, array(
                'action' => 'add'
            ));
        }

        $model = $this->getTableGateway()->get($key);

        $formClass = $this->formClass;
        $form = new $formClass();
        $form->bind($model);
        $form->get('submit')->setValue($this->label['edit']);
        $urlAction = $this->url()->fromRoute($this->route, array(
            'action' => 'edit',
            'key'    => $key
        ));

        return $this->save($model, $form, $urlAction, $key);
    }

    public function deleteAction()
    {
        $key = (int) $this->params()->fromRoute('key', null);
        if (is_null($key)) {
            return $this->redirect()->toRoute($this->route);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', $this->label['no']);

            if ($del == $this->label['yes']) {
                $this->getTableGateway()->delete($key);
            }

            return $this->redirect()->toRoute($this->route);
        }

        $urlAction = $this->url()->fromRoute($this->route, array('action' => 'delete', 'key' => $key));
        return array(
            'form'      => $this->getDeleteForm($key),
            'urlAction' => $urlAction,
            'title'     => $this->setAndGetTitle()
        );
    }

    public function save(AbstractModel $model, $form, $urlAction, $key)
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($model->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {

                $this->getTableGateway()->save($form->getData());

                return $this->Redirect()->toRoute($this->route);
            }
        }

        return array(
            'key'       => $key,
            'form'      => $form,
            'urlAction' => $urlAction,
            'title'     => $this->setAndGetTitle()
        );
    }

    public function getTableGateway()
    {
        if (!$this->tableGateway) {
            $sm = $this->getServiceLocator();
            $this->tableGateway = $sm->get($this->namespaceTableGateway);
        }

        return $this->tableGateway;
    }

    public function getDeleteForm($key)
    {
        $form = new Form();

        $form->add(array(
            'name' => 'del',
            'attributes' => array(
                'type'  => 'submit',
                'value' => $this->label['yes'],
                'id'    => 'del'
            )
        ));

        $form->add(array(
            'name' => 'return',
            'attributes' => array(
                'type'  => 'submit',
                'value' => $this->label['no'],
                'id'    => 'return'
            )
        ));

        return $form;
    }

    protected function getSm()
    {
        return $this->getEvent()->getApplication()->getServiceManager();
    }

    protected function setAndGetTitle()
    {
        $headTitle = $this->getSm()->get('viewhelpermanager')->get('HeadTitle');
        $headTitle($this->title);
        return $this->title;
    }
}