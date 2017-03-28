<?php
namespace Test\Github;

use Github\ApiInterface;
use Github\Client;
use Github\Api;
use Zend\Http\Response;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class ApiTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_clientMock;

    protected function setUp()
    {
        $this->_clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()->getMock();
    }

    public function testGetWillUseGithubClientAndWillReturnObject()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_200);
        $response->setContent("{}");

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/repos/$owner/$repository")
            ->willReturn($response);

        $api = new Api($this->_clientMock);
        $result = $api->get($owner, $repository);

        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testGetWillUseGithubClientAndWillReturnNull()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/repos/$owner/$repository")
            ->willReturn(null);

        $api = new Api($this->_clientMock);
        $result = $api->get($owner, $repository);

        $this->assertNull($result);
    }

    public function testLatestWillUseGithubClientAndWillReturnObject()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_200);
        $response->setContent("{}");

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/repos/$owner/$repository/releases/latest")
            ->willReturn($response);

        $api = new Api($this->_clientMock);
        $result = $api->latest($owner, $repository);

        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testLatestWillUseGithubClientAndWillReturnNullIfResponseIsEmpty()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/repos/$owner/$repository/releases/latest")
            ->willReturn(null);

        $api = new Api($this->_clientMock);
        $result = $api->latest($owner, $repository);

        $this->assertNull($result);
    }

    public function testGetPullRequestsWillUseGithubClientAndGetOpenPr()
    {
        $owner = 'phpunit';
        $repository = 'test';
        $is = ApiInterface::PR_IS_OPEN;

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/search/issues?q=+type:pr+repo:$owner/$repository+is:$is")
            ->willReturn(new Response());

        $api = new Api($this->_clientMock);
        $api->getPullRequests($owner, $repository, $is);
    }

    public function testGetPullRequestsWillUseGithubClientAndGetClosedPr()
    {
        $owner = 'phpunit';
        $repository = 'test';
        $is = ApiInterface::PR_IS_CLOSED;

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/search/issues?q=+type:pr+repo:$owner/$repository+is:$is")
            ->willReturn(new Response());

        $api = new Api($this->_clientMock);
        $api->getPullRequests($owner, $repository, $is);
    }

    public function testGetPullRequestsWillUseGithubClientAndGetMergedPr()
    {
        $owner = 'phpunit';
        $repository = 'test';
        $is = ApiInterface::PR_IS_MERGED;

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/search/issues?q=+type:pr+repo:$owner/$repository+is:$is")
            ->willReturn(new Response());

        $api = new Api($this->_clientMock);
        $api->getPullRequests($owner, $repository, $is);
    }

    public function testGetPullRequestsWillUseGithubClientAndReturnNullIfResponseEmpty()
    {
        $owner = 'phpunit';
        $repository = 'test';
        $is = ApiInterface::PR_IS_MERGED;

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/search/issues?q=+type:pr+repo:$owner/$repository+is:$is")
            ->willReturn(null);

        $api = new Api($this->_clientMock);
        $result = $api->getPullRequests($owner, $repository, $is);

        $this->assertNull($result);
    }

    public function testGetPullRequestsWillUseGithubClientAndReturnNullIfResponseNot200()
    {
        $owner = 'phpunit';
        $repository = 'test';
        $is = ApiInterface::PR_IS_MERGED;

        $response = new Response();
        $response->setStatusCode(Response::STATUS_CODE_404);

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/search/issues?q=+type:pr+repo:$owner/$repository+is:$is")
            ->willReturn($response);

        $api = new Api($this->_clientMock);
        $result = $api->getPullRequests($owner, $repository, $is);

        $this->assertNull($result);
    }

    public function testCheckIfRepoExistsWillUseUseGithubClient()
    {
        $owner = 'phpuni';
        $repository = 'test';

        $this->_clientMock->expects($this->once())
            ->method('head')
            ->with("/repos/$owner/$repository");

        $api = new Api($this->_clientMock);
        $api->checkIfRepoExists($owner, $repository);
    }

}