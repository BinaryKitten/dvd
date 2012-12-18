<?php

namespace Movie;
use Zend\ServiceManager\ServiceManager;
use ZendService\Amazon\Amazon as AmazonService;
use Movie\DataSource\Database as DbDataSource;
use Movie\DataSource\Amazon as AmazonDataSource;

class Module
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

    public function onBootstrap($e)
    {
        $sharedEvents = $e->getApplication()->getServiceManager()->get('moduleManager')->getEventManager()->getSharedManager();
        $sharedEvents->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
            $controller   = $e->getTarget();
            $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
            $allowedRoutes = array('zfcuser/login', 'zfcuser/register');
            if (in_array($matchedRoute, $allowedRoutes) || $controller->zfcUserAuthentication()->hasIdentity()) {
                return; // they're logged in or on the login page, allow
            }
            // otherwise, redirect to the login page
            return $controller->redirect()->toRoute('zfcuser/login');
        });
    }

    public function getServiceConfig()
    {
        return array(
            'aliases' => array(
                'MovieSource' => 'MovieSource\Database'
            ),
            'factories' => array(
                'MovieSource\Amazon' => function($sm) {
                    $service        = $sm->get('ZendService\Amazon\Amazon');
                    $associateTag   = $sm->get('amazon_associate_tag');
                    return new AmazonDataSource($service, $associateTag);
                },
                'MovieSource\Database' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new DbDataSource($dbAdapter);
                    return $table;
                },
                'logger' => function($sm) {
                    $writer = new \Zend\Log\Writer\Stream('data/log/movie.log');
                    $logger = new \Zend\Log\Logger();
                    $logger->addWriter($writer);
                    return $logger;
                },
                'ZendService\Amazon\Amazon' => function(ServiceManager $sm) {
                    $fullconfig         = $sm->get('config');
                    $amazonApiDetails   = $fullconfig['amazon_api_details'];
                    $amazon             = new AmazonService(
                        $amazonApiDetails['api_key'],
                        $amazonApiDetails['region'],
                        $amazonApiDetails['secret_key']
                    );
                    return $amazon;
                },
                'amazon_associate_tag' => function(ServiceManager $sm) {
                    $fullconfig         = $sm->get('config');
                    $amazonApiDetails   = $fullconfig['amazon_api_details'];
                    return $amazonApiDetails['associate_tag'];
                },
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
