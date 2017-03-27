<?php

namespace Api;

use Api\Model\Mapper\RepositoryMapper;
use Api\Service\GithubRepositoriesService;
use Api\Service\StatusService;
use Github\Client;
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
                \Zend\Cache\Storage\Adapter\Redis::class => function($container) {
                    $cache   = \Zend\Cache\StorageFactory::factory([
                        'adapter' => array(
                            'name' => 'redis',
                            'options' => [
                                'server' => [
                                    'host' => 'localhost',
                                    'port' => '6379'
                                ],
                            ]
                        ),
                        'plugins' => [
                            // Don't throw exceptions on cache errors
                            'exception_handler' => [
                                'throw_exceptions' => false
                            ],
                        ]
                    ]);

                    return $cache;
                },
                StatusService::class => function ($container) {
                    return new StatusService(new Uptime());
                },
                GithubRepositoriesService::class => function($container) {
                    $githubRepositoriesApi = new \Github\RepositoriesApi(
                        new Client(
                            new \Zend\Http\Client()
                        )
                    );

                    $service = new GithubRepositoriesService($githubRepositoriesApi);
                    $service->setCacheAdapter(
                        $container->get(\Zend\Cache\Storage\Adapter\Redis::class)
                    );
                    $service->setRepositoryMapper(new RepositoryMapper());

                    return $service;
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
                Controller\ReposController::class => function ($container) {
                    $service = $container->get(GithubRepositoriesService::class);
                    return new Controller\ReposController($service);
                },
            ],
        ];
    }
}
