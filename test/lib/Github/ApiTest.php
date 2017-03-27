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

    public function testGetWillUseGithubClient()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/repos/$owner/$repository")
            ->willReturn(new Response());

        $api = new Api($this->_clientMock);
        $api->get($owner, $repository);
    }

    public function testLatestWillUseGithubClient()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $this->_clientMock->expects($this->once())
            ->method('get')
            ->with("/repos/$owner/$repository/releases/latest")
            ->willReturn(new Response());

        $api = new Api($this->_clientMock);
        $api->latest($owner, $repository);
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

}