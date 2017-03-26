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

        $statusServiceMock = $this->getMockBuilder(\Api\Service\StatusService::class)
            ->disableOriginalConstructor()->getMock();

        $serviceManagerMock->method('get')
            ->with(\Api\Service\StatusService::class)
            ->willReturn($statusServiceMock);

        $this->assertInstanceOf(
            \Api\Controller\IndexController::class,
            $result['factories'][\Api\Controller\IndexController::class]($serviceManagerMock)
        );
    }

}