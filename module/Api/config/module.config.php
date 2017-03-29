<?php

namespace Api;

use Api\Controller\RepoController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;


return [
    'router' => [
        'routes' => [
            'api-status' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'repository' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/repo/:id',
                    'constraints' => [
                        'id' => '[a-zA-Z0-9_-]+/[a-zA-Z0-9_\-\.]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\RepoController::class,
                    ],
                ],
                'may_terminate' => false,
            ],
            'compare' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/compare/:id',
                    'constraints' => [
                        'id' => '[a-zA-Z0-9_-]+/[a-zA-Z0-9_\-\.]+,[a-zA-Z0-9_-]+/[a-zA-Z0-9_\-\.]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\CompareController::class
                    ],
                ],
                'may_terminate' => true
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'strategies' => [
            'ViewJsonStrategy',
        ],
        'template_map' => [
            'layout/layout' => realpath(__DIR__ . '/../src/view/layout/layout.phtml'),
            'error/index' => __DIR__ . '/../src/view/error/error.phtml',
            'error/404' => __DIR__ . '/../src/view/error/404.phtml',
        ],
        'template_path_stack' => array(
            'api' => __DIR__ . '/../view',
        )
    ],
];