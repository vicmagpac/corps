<?php

namespace Fgsl\Mvc\Controller;

use Tropa\Form\SetorForm;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

abstract class AbstractDoctrineCrudControlle extends AbstractActionController
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

        $placeHolder = $this->getSm()->get('viewhelpermanagaer')->get('PlaceHolder');
        $placeHolder('url')->edit = $urlEdit;
        $placeHolder('url')->delete = $urlDelete;

        $em = $GLOBALS['entityManager'];
        $result = $em->getRepository($this->modelClass)->findAll();

        $pageAdapter = new ArrayAdapter($result);
        $paginator = new Paginator($pageAdapter);
        $paginator->setCurrentPageNumber($this->params()->fromRoute('page'), 1);

        return new ViewModel(array(
            'paginator' => $paginator,
            'title'     => $this->setAndGetTitle(),
            'urlAdd'    => $urlAdd,
            'urlHomepage'   => $urlHomepage
        ));
    }

    public function addAction()
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass(
            $this->getTableGateway()->getPrimaryKey(),
            $this->getTableGateway()->getTable(),
            $this->getTableGateway()->getAdapter(), false
        );

        $formClass = $this->formClass;
        $form = new $formClass();
        $form->get('submit')->setValue($this->label['add']);
        $form->bind($model);

        $urlAction = $this->url()->fromRoute($this->route, array('action' => 'add'));
        return $this->save($model, $form, $urlAction, null);
    }

    public function getSm()
    {
        return $this->getEvent()->getApplication()->getServiceManager();
    }

    public function setAndGetTitle()
    {
        $headTitle = $this->getSm()->get('viewhelpermanager')->get('HeadTitle');
        $headTitle($this->title);
        return $this->title;
    }
}