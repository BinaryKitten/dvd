<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Dvd\Controller\Dvd' => 'Dvd\Controller\DvdController',
        ),
    ),
    
    'router' => array(
        'routes' => array(
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
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
);