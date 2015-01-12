<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Tropa;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Tropa\Model\Lanterna;
use Tropa\Model\LanternaTable;
use Tropa\Model\Setor;
use Tropa\Model\SetorTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Filter\RealPath;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;

class Module
{
    const DOCTRINE_BASE_PATH = '/../../vendor/doctrine/orm/lib/Doctrine';

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

        // inicializando o doctrine
        $this->initializeDoctrine2($e);
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
                    'Fgsl' => realpath(__DIR__ . '/../../vendor/fgslframework/fgslframework/library/Fgsl'),
                    'Doctrine\Common' => realpath(__DIR__ . self::DOCTRINE_BASE_PATH . '/Common'),
                    'Doctrine\DBAL'   => realpath(__DIR__ . self::DOCTRINE_BASE_PATH . '/DBAL'),
                    'Doctrine\ORM'    => realpath(__DIR__ . self::DOCTRINE_BASE_PATH . '/ORM')
                ),
            ),
        );
    }

    private function getDoctrine2Config($e)
    {
        $config = $e->getApplication()->getConfig();
        return $config['doctrine_config'];
    }

    private function initializeDoctrine2($e)
    {
        $conn = $this->getDoctrine2Config($e);
        $config = new Configuration();
        $cache = new ArrayCache();

        $config->setMetadataCacheImpl($cache);

        $annotationPath = realpath(__DIR__ . self::DOCTRINE_BASE_PATH . '/ORM/Mapping/Driver/DoctrineAnnotations.php');
        AnnotationRegistry::registerFile($annotationPath);

        $driver = new AnnotationDriver(
            new AnnotationReader(),
            array(__DIR__ . '/src/Tropa/Model')
        );
        $config->setMetadataDriverImpl($driver);
        $config->setProxyDir(__DIR__ . '/src/Tropa/Proxy');
        $config->setProxyNamespace('Tropa\Proxy');

        $entityManager = EntityManager::create($conn, $config);
        $GLOBALS['entityManager'] = $entityManager;
    }
}
