<?php

namespace Application\Model;

use Doctrine\ORM\Mapping as ORM;
use Fgsl\Authentication\Adapter\DoctrineTable;
use Zend\Authentication\AuthenticationService;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuarios")
 */
class Usuario
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $uid;

    /**
     * @ORM\Column(type="string")
     */
    private $identidade;

    /**
     * @ORM\Column(type="string")
     */
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
        $adapter = new DoctrineTable($GLOBALS['entityManager']);
        $adapter->setEntityName(__CLASS__)
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