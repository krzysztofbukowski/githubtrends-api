<?php

namespace Api;

use Api\Model\Mapper\RepositoryMapper;
use Api\Service\GithubService;
use Api\Service\StatusService;
use function foo\func;
use Github\Client;
use Utils\Uptime;
use Zend\Http\Request;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Listener\OnBootstrapListener;

class Module implements ConfigProviderInterface
{
    const VERSION = '1.0.0';
    const NAME = 'Api';

    /**
     * @var Request
     */
    protected $_req;

    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {

        $e->getApplication()->getEventManager()->attach(\Zend\Mvc\MvcEvent::EVENT_ROUTE,
            array($this, 'log'));

    }

    public function log(\Zend\Mvc\MvcEvent $a)
    {
        $application = $a->getApplication();
        $this->_req = $application->getRequest();
        $logger = $application->getServiceManager()->get('logger');
        $logger->debug(
            join(
                " ",
                [
                    $this->_req->getMethod(),
                    $this->_req->getUri()
                ]
            )
        );
    }

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
                \Zend\Cache\Storage\Adapter\Redis::class => function ($container) {
                    $cache = \Zend\Cache\StorageFactory::factory([
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
                GithubService::class => function ($container) {
                    $logger = $container->get('logger');
                    $cacheAdapter = $container->get(\Zend\Cache\Storage\Adapter\Redis::class);

                    $client = new Client(new \Zend\Http\Client());
                    $client->setLogger($logger);

                    $githubRepositoriesApi = new \Github\Api($client);

                    $service = new GithubService($githubRepositoriesApi);
                    $service->setCacheAdapter($cacheAdapter);
                    $service->setRepositoryMapper(new RepositoryMapper());
                    $service->setLogger($logger);

                    return $service;
                },
                'logger' => function ($container) {
                    $logger = new \Zend\Log\Logger();
                    $writer = new \Zend\Log\Writer\Stream("/var/log/githubtrends.pl/app.log");
                    $logger->addWriter($writer);

                    return $logger;
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
                Controller\RepoController::class => function ($container) {
                    $service = $container->get(GithubService::class);
                    return new Controller\RepoController($service);
                },
                Controller\CompareController::class => function ($container) {
                    $service = $container->get(GithubService::class);
                    return new Controller\CompareController($service);
                },
            ],
        ];
    }
}
