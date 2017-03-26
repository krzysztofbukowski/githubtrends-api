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

    protected function setUp()
    {
        $this->_module = new \Api\Module();
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

        $mock = $this->getMockBuilder(\Zend\ServiceManager\ServiceManager::class)
            ->getMock();

        $this->assertInstanceOf(
            \Api\Service\StatusService::class,
                $result['factories'][\Api\Service\StatusService::class]($mock)
        );
    }

    public function testGetServiceConfigCreatesGithubRepositoriesService()
    {
        $result = $this->_module->getServiceConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);

        $mock = $this->getMockBuilder(\Zend\ServiceManager\ServiceManager::class)
            ->getMock();

        $this->assertInstanceOf(
            \Api\Service\GithubRepositoriesService::class,
            $result['factories'][\Api\Service\GithubRepositoriesService::class]($mock)
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

        $serviceManagerMock = $this->getMockBuilder(\Zend\ServiceManager\ServiceManager::class)
            ->getMock();

        $serviceMock = $this->getMockBuilder(\Api\Service\StatusService::class)
            ->disableOriginalConstructor()->getMock();

        $serviceManagerMock->method('get')
            ->with(\Api\Service\StatusService::class)
            ->willReturn($serviceMock);

        $this->assertInstanceOf(
            \Api\Controller\IndexController::class,
            $result['factories'][\Api\Controller\IndexController::class]($serviceManagerMock)
        );
    }

    public function testGetControllerConfigCreatesReposController()
    {
        $result = $this->_module->getControllerConfig();
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('factories', $result);

        $serviceManagerMock = $this->getMockBuilder(\Zend\ServiceManager\ServiceManager::class)
            ->getMock();

        $serviceMock = $this->getMockBuilder(\Api\Service\GithubRepositoriesService::class)
            ->disableOriginalConstructor()->getMock();

        $serviceManagerMock->method('get')
            ->with(\Api\Service\GithubRepositoriesService::class)
            ->willReturn($serviceMock);

        $this->assertInstanceOf(
            \Api\Controller\ReposController::class,
            $result['factories'][\Api\Controller\ReposController::class]($serviceManagerMock)
        );
    }

}