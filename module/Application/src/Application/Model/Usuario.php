<?php

namespace Application\Model;

use Zend\Authentication\Adapter;
USE Zend\Authentication\Adapter\DbTable;
use Zend\Authentication\AuthenticationService;

class Usuario
{
    private $identidade;
    private $credencial;

    public $messages = array();

    public function __construct($identidade, $credencial)
    {
        $this->identidade = $identidade;
        $this->credencial = $credencial;
    }

    public function authenticate($sm)
    {
        // cria o adaptador para o mecanismo contra o qual se ferá a autenticação
        $zendDb = $sm->get('Zend\Db\Adapter\Adapter');
        $adapter = new DbTable($zendDb);
        $adapter->setTableName('usuarios')
                ->setIdentityColumn('identidade')->setIdentity($this->identidade)
                ->setCredentialColumn('credencial')->setCredential($this->credencial);

        // criar o serviço de autenticação e injeta o adaptador nele
        $authentication = new AuthenticationService();
        $authentication->setAdapter($adapter);

        // autentica
        $result = $authentication->authenticate();
        if ($result->isValid()) {
            // recupera o registro do usuário como um objeto, sem o campo senha
            $usuario = $authentication->getAdapter()->getResultRowObject(null, 'senha');
            $authentication->getStorage()->write($usuario);
            return true;
        } else {
            $this->messages = $result->getMessages();
            return false;
        }

    }
}