<?php

namespace Api;

use Api\Service\StatusService;
use Utils\Uptime;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    const VERSION = '1.0.0';
    const NAME = 'Api';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                StatusService::class => function ($container) {
                    return new StatusService(new Uptime());
                }
            ]
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function ($container) {
                    $service = $container->get(StatusService::class);
                    return new Controller\IndexController($service);
                },
            ],
        ];
    }
}
