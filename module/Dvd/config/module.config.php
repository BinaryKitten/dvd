<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Dvd\Controller\Index'  => 'Dvd\Controller\IndexController',
            'Dvd\Controller\Dvd'    => 'Dvd\Controller\DvdController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Dvd\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'dvd' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/dvd[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Dvd\Controller\Dvd',
                        'action'     => 'view',
                    ),
                ),
            ),
        ),
    ),

   'view_manager' => array(
        'display_not_found_reason'  => true,
        'display_exceptions'        => true,
        'doctype'                   => 'HTML5',
        'not_found_template'        => 'error/404',
        'exception_template'        => 'error/index',
        'template_map' => array(
            'layout/layout'         => __DIR__ . '/../view/layout/layout.phtml',
            'dvd/index/index'       => __DIR__ . '/../view/dvd/index/index.phtml',
            'error/404'             => __DIR__ . '/../view/error/404.phtml',
            'error/index'           => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);