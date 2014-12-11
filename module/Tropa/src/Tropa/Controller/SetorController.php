<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tropa\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SetorController extends AbstractActionController
{
    protected $setorTable;

    public function indexAction()
    {
        return new ViewModel();
    }

    public function addAction()
    {

    }

    public function editAction()
    {

    }

    public function deleteAction()
    {

    }

    public function getSetorController()
    {
        if (!$this->setorTable) {
            $sm = $this->getServiceLocator();
            $this->setorTable = $sm->get('Tropa\Model\SetorTable');
        }

        return $this->setorTable;
    }
}
