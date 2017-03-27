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

    /**
     * @var
     */
    protected $_httpClientMock;

    protected function setUp()
    {
        $this->_httpClientMock = $this->getMockBuilder(\Zend\Http\Client::class)
            ->disableOriginalConstructor()->getMock();

        $this->_client = new Client($this->_httpClientMock);
    }


    public function testGetWillUseHttpClientToMakeRequest()
    {
        $path = '/some/path';

        $this->_httpClientMock->method('send')
            ->willReturn(new Response());

        $this->_httpClientMock->method('setUri')
            ->with(Client::API_HOST . $path);

        $this->_client->get($path);
    }

    public function testGetWillReturnObjectOnSuccess()
    {
        $path = '/some/path';

        $response = new Response();
        $response->setContent(json_encode(new \stdClass()));

        $this->_httpClientMock->method('send')
            ->willReturn($response);

        $this->_httpClientMock->method('setUri')
            ->with(Client::API_HOST . $path);

        $result = $this->_client->get($path);
        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testGetWillReturnNullOnFailure()
    {
        $path = '/some/path';

        $this->_httpClientMock->method('send')
            ->willThrowException(new \Exception());
        $this->_httpClientMock->method('setUri')
            ->with(Client::API_HOST . $path);

        $result = $this->_client->get($path);
        $this->assertNull($result);
    }

    public function testGetWillReturnNullIfResponseStatusCodeIsNot200 () {
        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_400);

        $path = '/some/path';
        $this->_httpClientMock->method('send')
            ->willReturn($response);

        $result = $this->_client->get($path);
        $this->assertNull($result);
    }

}