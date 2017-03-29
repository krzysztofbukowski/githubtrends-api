<?php

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class ModuleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Api\Module
     */
    protected $_module;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $_serviceManagerMock;

    protected function setUp()
    {
        $this->_module = new \Api\Module();
        $this->_serviceManagerMock = $this->getMockBuilder(
            \Zend\ServiceManager\ServiceManager::class
        )->getMock();
    }

    public function testGetServiceConfigReturnsArray()
    {
        $result = $this->_module->getServiceConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);
    }

    // ----------- service factories tests ---------------------

    public function testGetServiceConfigCreatesStatusService()
    {
        $result = $this->_module->getServiceConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);

        $this->assertInstanceOf(
            \Api\Service\StatusService::class,
                $result['factories'][\Api\Service\StatusService::class]($this->_serviceManagerMock)
        );
    }

    public function testGetServicesConfigCreatesRedisAdapter()
    {
        $result = $this->_module->getServiceConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);

        $this->assertInstanceOf(
            \Zend\Cache\Storage\Adapter\Redis::class,
            $result['factories'][\Zend\Cache\Storage\Adapter\Redis::class]($this->_serviceManagerMock)
        );
    }

    public function testGetServiceConfigCreatesGithubService()
    {
        $result = $this->_module->getServiceConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);

        $redisAdapterMock = $this->getMockBuilder(\Zend\Cache\Storage\Adapter\Redis::class)
        ->disableOriginalConstructor()->getMock();

        $loggerMock = $this->getMockBuilder(\Zend\Log\Logger::class)
            ->getMock();

        $this->_serviceManagerMock->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                ['logger'],
                [\Zend\Cache\Storage\Adapter\Redis::class]
            )
            ->willReturnOnConsecutiveCalls($loggerMock, $redisAdapterMock);

        $this->assertInstanceOf(
            \Api\Service\GithubService::class,
            $result['factories'][\Api\Service\GithubService::class]($this->_serviceManagerMock)
        );
    }

    // ----------- controller factories tests ---------------------

    public function testGetControllerConfigReturnsArray()
    {
        $result = $this->_module->getControllerConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);
    }


    public function testGetControllerConfigCreatesIndexController()
    {
        $result = $this->_module->getControllerConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);

        $serviceMock = $this->getMockBuilder(\Api\Service\StatusService::class)
            ->disableOriginalConstructor()->getMock();

        $this->_serviceManagerMock->method('get')
            ->with(\Api\Service\StatusService::class)
            ->willReturn($serviceMock);

        $this->assertInstanceOf(
            \Api\Controller\IndexController::class,
            $result['factories'][\Api\Controller\IndexController::class]($this->_serviceManagerMock)
        );
    }

    public function testGetControllerConfigCreatesReposController()
    {
        $result = $this->_module->getControllerConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);

        $serviceMock = $this->getMockBuilder(\Api\Service\GithubService::class)
            ->disableOriginalConstructor()->getMock();

        $this->_serviceManagerMock->method('get')
            ->with(\Api\Service\GithubService::class)
            ->willReturn($serviceMock);

        $this->assertInstanceOf(
            \Api\Controller\RepoController::class,
            $result['factories'][\Api\Controller\RepoController::class]($this->_serviceManagerMock)
        );
    }

}