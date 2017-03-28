<?php
namespace Test\Github;

use Github\Client;
use Zend\Http\Request;
use Zend\Http\Response;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $_client;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_httpClientMock;

    protected function setUp()
    {
        $this->_httpClientMock = $this->getMockBuilder(\Zend\Http\Client::class)
            ->disableOriginalConstructor()->getMock();

        $this->_client = new Client($this->_httpClientMock);
    }


    public function testGetWillUseHttpClientToSendAGetRequest()
    {
        $path = '/some/path';

        $this->_httpClientMock->expects($this->once())
            ->method('send')
            ->willReturn(new Response());

        $this->_httpClientMock->expects($this->once())
            ->method('setUri')
            ->with(Client::API_HOST . $path);

        $this->_httpClientMock->expects($this->once())
            ->method('setMethod')
            ->with(Request::METHOD_GET);

        $this->_client->get($path);
    }


    public function testHeadWillUseHttpClientToSendAHeadRequest()
    {
        $path = '/some/path';

        $this->_httpClientMock->expects($this->once())
            ->method('send')
            ->willReturn(new Response());

        $this->_httpClientMock->expects($this->once())
            ->method('setUri')
            ->with(Client::API_HOST . $path);

        $this->_httpClientMock->expects($this->once())
            ->method('setMethod')
            ->with(Request::METHOD_HEAD);

        $this->_client->head($path);
    }

}