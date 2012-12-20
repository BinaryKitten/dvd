<?php

namespace BKSimpleAcl;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as ZendAclRole;
use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManager;
use Zend\Mvc\MvcEvent;

/**
 * BKSimpleAcl
 *
 * @author Kathryn Reeve <Kat@BinaryKitten.com>
 */
class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'BkSimpleAcl' => function (ServiceManager $sm) {
                    $acl    = new ZendAcl();
                   
                    $eventManager = $sm->get('moduleManager')->getEventManager();
                    $responses = $eventManager->trigger('binary_acl',null, array('acl'=>$acl));
                    \Zend\Debug\Debug::dump($responses, 'Responses');
                    return $acl;
                }
            )
        );
    }

    function onBootstrap(MvcEvent $e)
    {
        // 'bootstrap' is an application event, the target is the application
        $application = $e->getTarget();
        // we want to lock any access to controllers to users that are not recognized
        $eventManager = $application->getEventManager();
        $eventManager->attach(
            'dispatch',
            function (MvcEvent $e) use ($application) {
                // 'Some\Checker' is a service that is able to check the current login status
                $acl = $application->getServiceManager()->get('BkSimpleAcl');
//                if (!->check()) {
//                    throw new BadMethodException('Your identity was not verified');
//                }
            },
            1000
        );
    }
}