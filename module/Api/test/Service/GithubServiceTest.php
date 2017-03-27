<?php

namespace ApiTest\Service;

use Api\Model\Mapper\RepositoryMapper;
use Api\Model\Repository;
use Api\Service\GithubService;
use Github\Api;
use Github\ApiInterface;
use Zend\Log\Logger;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class GithubServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var GithubService
     */
    protected $_service;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_repositoriesApiMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_repositoryMapperMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_cacheAdapterMock;

    public function setUp()
    {
        $this->_repositoriesApiMock = $this->getMockBuilder(Api::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->_repositoryMapperMock = $this->getMockBuilder(RepositoryMapper::class)
            ->getMock();
        $this->_repositoryMapperMock->method('map')
            ->willReturn(new Repository());


        $this->_cacheAdapterMock = $this->getMockBuilder(\Zend\Cache\Storage\Adapter\Redis::class)
            ->disableOriginalConstructor()->getMock();


        $this->_service = new GithubService($this->_repositoriesApiMock);
        $this->_service->setRepositoryMapper($this->_repositoryMapperMock);
        $this->_service->setCacheAdapter($this->_cacheAdapterMock);
    }

    public function testGetRepositoryMapper()
    {
        $result = $this->_service->getRepositoryMapper();
        $this->assertInstanceOf(RepositoryMapper::class, $result);
    }

    public function testCompareWillUseGithubClient()
    {
        $this->_service->compare('owner/repo1', 'owner/repo2');
    }

    public function testCompareWillReturnResult()
    {
        $result = $this->_service->compare('owner/repo1', 'owner/repo2');

        $this->assertInternalType('array', $result);
    }

    public function testCompareWillLoadDataFromCache()
    {
        $data = [1, 2, 3];

        $this->_cacheAdapterMock->method('getItem')
            ->with("repos_owner/repo1,owner/repo2")
            ->willReturn(json_encode($data));

        $result = $this->_service->compare('owner/repo1', 'owner/repo2');
        $this->assertEquals($result, $data);
    }

    public function testCompareWillSaveDataInCacheAndReturnIt()
    {
        $data = [null, null];

        $this->_repositoryMapperMock->method('map')
            ->willReturn(new Repository());


        $this->_cacheAdapterMock->method('getItem')
            ->with("repos_owner/repo1,owner/repo2")
            ->willReturn(null);

        $this->_cacheAdapterMock->method('setItem')
            ->with("repos_owner/repo1,owner/repo2", json_encode($data))
            ->willReturn(true);

        $result = $this->_service->compare('owner/repo1', 'owner/repo2');
        $this->assertEquals($result, $data);
    }

    public function testCompareWillLogDataIfLoggerIsAvailable()
    {
        $loggerMock = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()->getMock();

        $loggerMock->expects($this->any())
            ->method('debug');

        $this->_service->setLogger($loggerMock);
        $result = $this->_service->compare('owner/repo', 'owner2/repo2');
    }

    // -------------- checkRepoIfExists tests --------------------------------------

    public function testCheckIfRepoExistsWillUseGithubApiAndReturnTrue()
    {
        $this->_repositoriesApiMock->expects($this->once())
            ->method('checkIfRepoExists')
            ->willReturn(true);

        $result = $this->_service->checkIfRepoExists('phpunit/test');

        $this->assertTrue($result);
    }

    public function testCheckIfRepoExistsWillUseGithubApiAndReturnFalse()
    {
        $this->_repositoriesApiMock->expects($this->once())
            ->method('checkIfRepoExists')
            ->willReturn(false);

        $result = $this->_service->checkIfRepoExists('phpunit/test');

        $this->assertFalse($result);
    }

    public function testCheckIfRepoExistsWillUseGithubApiAndReturnFalseIfResponseIsEmpty()
    {
        $this->_cacheAdapterMock->expects($this->once())
            ->method('getItem')
            ->willReturn(null);

        $this->_repositoriesApiMock->expects($this->once())
            ->method('checkIfRepoExists')
            ->willReturn(false);

        $result = $this->_service->checkIfRepoExists('phpunit/test');

        $this->assertFalse($result);
    }

    public function testCheckIfRepoExistsWillUseGithubApiAndReturnFalseIfResponseIsNotFound()
    {
        $this->_cacheAdapterMock->expects($this->once())
            ->method('getItem')
            ->willReturn('not_found');

        $result = $this->_service->checkIfRepoExists('phpunit/test');

        $this->assertFalse($result);
    }


    // -------------- getLatest tests --------------------------------------

    public function testGetLatestWillCallTheLatestApiEndpointAndReturnResultReturnedByApi()
    {
        $expected = new \stdClass();

        $this->_repositoriesApiMock->expects($this->once())
            ->method('latest')
            ->willReturn($expected);

        $result = $this->_service->getLatestRelease('phpunit', 'test');

        $this->assertEquals($expected, $result);
    }

    // -------------- getOpenPullRequests tests --------------------------------------

    public function testGetOpenPullRequestsWillCallTheLatestApiEndpointAndReturnResultReturnedByApi()
    {
        $expected = new \stdClass();

        $this->_repositoriesApiMock->expects($this->once())
            ->method('getPullRequests')
            ->with('phpunit', 'test', ApiInterface::PR_IS_OPEN)
            ->willReturn($expected);

        $result = $this->_service->getOpenPullRequests('phpunit', 'test');

        $this->assertEquals($expected, $result);
    }

    // -------------- getClosedPullRequests tests --------------------------------------

    public function testGetClosedPullRequestsWillCallTheLatestApiEndpointAndReturnResultReturnedByApi()
    {
        $expected = new \stdClass();

        $this->_repositoriesApiMock->expects($this->once())
            ->method('getPullRequests')
            ->with('phpunit', 'test', ApiInterface::PR_IS_CLOSED)
            ->willReturn($expected);

        $result = $this->_service->getClosedPullRequests('phpunit', 'test');

        $this->assertEquals($expected, $result);
    }

    // -------------- getClosedPullRequests tests --------------------------------------

    public function testGetMergedPullRequestsWillCallTheLatestApiEndpointAndReturnResultReturnedByApi()
    {
        $expected = new \stdClass();

        $this->_repositoriesApiMock->expects($this->once())
            ->method('getPullRequests')
            ->with('phpunit', 'test', ApiInterface::PR_IS_MERGED)
            ->willReturn($expected);

        $result = $this->_service->getMergedPullRequests('phpunit', 'test');

        $this->assertEquals($expected, $result);
    }

    // -------------- getRepoDetails tests --------------------------------------

    public function testGetRepoDetailsWillReturnResult()
    {
        $expected = new \stdClass();

        $this->_repositoriesApiMock->expects($this->once())
            ->method('get')
            ->with('phpunit', 'test')
            ->willReturn($expected);

        $result = $this->_service->getRepoDetails('phpunit/test');

        $this->assertNotNull($result);
    }
}