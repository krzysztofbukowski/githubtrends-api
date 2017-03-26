<?php

namespace ApiTest\Service;
use Api\Model\Status;
use Api\Module;
use Api\Service\StatusService;
use Utils\Uptime;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class StatusServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_statusService;

    public function testGetStatusReturnsStatusObject()
    {

        $uptime = $this->getMockBuilder(Uptime::class)
            ->setMethods(array('calculate'))->getMock();
        $uptime->method('calculate')
            ->with(Uptime::HTTPD_PID)
            ->willReturn(100);

        $this->_statusService = new StatusService($uptime);

        $result = $this->_statusService->getStatus();

        $this->assertInstanceOf(Status::class, $result);
    }

    public function testGetStatusUpdatesStatusProperties()
    {

        $uptime = $this->getMockBuilder(Uptime::class)
            ->setMethods(array('calculate'))->getMock();
        $uptime->method('calculate')
            ->with(Uptime::HTTPD_PID)
            ->willReturn(100);

        $this->_statusService = new StatusService($uptime);

        $result = $this->_statusService->getStatus();
        $this->assertInstanceOf(Status::class, $result);

        $this->assertEquals(100, $result->getUptime());
        $this->assertEquals(Module::VERSION, $result->getVersion());
    }

}