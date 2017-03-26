<?php
namespace Test\Github;

use Github\Client;
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

    protected function setUp()
    {

    }


    public function testGetWillUseHttpClientToMakeRequest()
    {
        $path = '/some/path';

        $httpClientMock = $this->getMockBuilder(\Zend\Http\Client::class)
            ->disableOriginalConstructor()->getMock();

        $httpClientMock->method('send')
            ->willReturn(new Response());

        $httpClientMock->method('setUri')
            ->with(Client::API_HOST . $path);

        $this->_client = new Client($httpClientMock);

        $this->_client->get($path);
    }

    public function testGetWillReturnObjectOnSuccess()
    {
        $path = '/some/path';

        $httpClientMock = $this->getMockBuilder(\Zend\Http\Client::class)
            ->disableOriginalConstructor()->getMock();

        $response = new Response();
        $response->setContent(json_encode(new \stdClass()));

        $httpClientMock->method('send')
            ->willReturn($response);

        $httpClientMock->method('setUri')
            ->with(Client::API_HOST . $path);

        $this->_client = new Client($httpClientMock);

        $result = $this->_client->get($path);

        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testGetWillReturnNullOnFailure()
    {
        $path = '/some/path';

        $httpClientMock = $this->getMockBuilder(\Zend\Http\Client::class)
            ->disableOriginalConstructor()->getMock();

        $httpClientMock->method('send')
            ->willThrowException(new \Exception());

        $httpClientMock->method('setUri')
            ->with(Client::API_HOST . $path);

        $this->_client = new Client($httpClientMock);

        $result = $this->_client->get($path);

        $this->assertNull($result);
    }

}