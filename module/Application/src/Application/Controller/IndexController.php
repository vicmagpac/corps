<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\Usuario;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $form = new LoginForm();
        $form->setAttribute('action', $this->getRequest()->getBaseUrl() . '/application/index/login');

        $messages = null;

        if ($this->flashMessenger()->getMessages()) {
            $messages = implode(',', $this->flashMessenger()->getMessages());
        }

        return array(
            'form'      => $form,
            'messages'  => $messages
        );
        //return new ViewModel();
    }

    public function loginAction()
    {
        $request = $this->getRequest();

        $identidade = $request->getPost('identidade');
        $credencial = $request->getPost('credencial');

        $usuario = new Usuario($identidade, $credencial);

        if ($usuario->authenticate($this->getServiceLocator())) {
            return $this->redirect()->toUrl('menu');
        } else {

        }

        $this->flashMessenger()->addMessage(implode(',', $usuario->messages));
        return $this->redirect()->toUrl('/');
    }

    public function menuAction()
    {
        $authentication = new AuthenticationService();
        return array('usuario' => $authentication->getIdentity());
    }

    public function logoutAction()
    {
        $authentication = new AuthenticationService();
        $authentication->clearIdentity();
        return $this->redirect()->toRoute('home');
    }
}
