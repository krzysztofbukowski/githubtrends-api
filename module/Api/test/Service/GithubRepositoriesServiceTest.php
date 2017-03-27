<?php

namespace ApiTest\Service;
use Api\Model\Mapper\RepositoryMapper;
use Api\Model\Repository;
use Api\Service\GithubRepositoriesService;
use Github\RepositoriesApi;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class GithubRepositoriesServiceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var GithubRepositoriesService
     */
    protected $_service;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_repositoriesApiMock;

    public function setUp() {
        $this->_repositoriesApiMock = $this->getMockBuilder(RepositoriesApi::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->_repositoriesApiMock->method('getRepoDetails')
            ->willReturn(null);

        $repositoryMapperMock = $this->getMockBuilder(RepositoryMapper::class)
            ->getMock();
        $repositoryMapperMock->method('map')
            ->willReturn(new Repository());


        $this->_service = new GithubRepositoriesService($this->_repositoriesApiMock);
        $this->_service->setRepositoryMapper($repositoryMapperMock);
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

        $cacheAdapterMock = $this->getMockBuilder(\Zend\Cache\Storage\Adapter\Redis::class)
            ->disableOriginalConstructor()->getMock();
        $this->_service->setCacheAdapter($cacheAdapterMock);

        $cacheAdapterMock->method('getItem')
            ->with("repos_owner/repo1,owner/repo2")
            ->willReturn(json_encode($data));

        $result = $this->_service->compare('owner/repo1', 'owner/repo2');
        $this->assertEquals($result, $data);
    }

    public function testCompareWillSaveDataInCacheAndReturnIt()
    {
        $data = [null, null];

        $cacheAdapterMock = $this->getMockBuilder(\Zend\Cache\Storage\Adapter\Redis::class)
            ->disableOriginalConstructor()->getMock();
        $this->_service->setCacheAdapter($cacheAdapterMock);

        $cacheAdapterMock->method('getItem')
            ->with("repos_owner/repo1,owner/repo2")
            ->willReturn(null);

        $cacheAdapterMock->method('setItem')
            ->with("repos_owner/repo1,owner/repo2", json_encode($data))
            ->willReturn(true);

        $result = $this->_service->compare('owner/repo1', 'owner/repo2');
        $this->assertEquals($result, $data);
    }

}