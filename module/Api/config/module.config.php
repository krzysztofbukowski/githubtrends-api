<?php

namespace Api;

use Api\Controller\IndexController;
use Api\Controller\ReposController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'api-status' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'repository' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/repos',
                    'defaults' => [
                        'controller' => Controller\ReposController::class
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'stats' => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/:id',
                            'constraints' => [
                                'id' => '[,a-zA-Z0-9_-]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\ReposController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            Controller\IndexController::class => IndexController::class,
            Controller\ReposController::class => ReposController::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];