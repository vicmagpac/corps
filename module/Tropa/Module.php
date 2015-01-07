<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tropa;

use Tropa\Model\Lanterna;
use Tropa\Model\LanternaTable;
use Tropa\Model\Setor;
use Tropa\Model\SetorTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');
        $translator->addTranslationFile(
            'phpArray',
            'vendor/zendframework/zendframework/resources/languages/pt_BR/Zend_Validate.php'
        );
        AbstractValidator::setDefaultTranslator($translator);

        $GLOBALS['sm'] = $e->getApplication()->getServiceManager();
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    'Fgsl' => realpath(__DIR__ . '/../../vendor/fgslframework/fgslframework/library/Fgsl')
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Tropa\Model\LanternaTable' => function($sm) {
                    $tableGateway = $sm->get('LanternaTableGateway');
                    $table = new LanternaTable($tableGateway);
                    return $table;
                },
                'Tropa\Model\SetorTable' => function($sm) {
                    $tableGateway = $sm->get('SetorTableGateway');
                    $table = new SetorTable($tableGateway);
                    return $table;
                },
                'LanternaTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Lanterna());
                    return new TableGateway('lanterna', $dbAdapter, null, $resultSetPrototype);
                },
                'SetorTableGateway' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Setor());
                    return new TableGateway('setor', $dbAdapter, null, $resultSetPrototype);
                }
            )
        );
    }
}
