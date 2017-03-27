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

    public function testCompareWillUseGithubClient()
    {
        $repositoriesApi = $this->getMockBuilder(RepositoriesApi::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoriesApi->method('getRepoDetails')
            ->willReturn(new \stdClass());

        $repositoryMapperMock = $this->getMockBuilder(RepositoryMapper::class)
            ->getMock();
        $repositoryMapperMock->method('map')
            ->willReturn(new Repository());

        $githubService = new GithubRepositoriesService($repositoriesApi);
        $githubService->setRepositoryMapper($repositoryMapperMock);
        $githubService->compare('owner/repo1', 'owner/repo2');
    }

    public function testCompareWillReturnResult()
    {
        $repositoriesApi = $this->getMockBuilder(RepositoriesApi::class)
            ->disableOriginalConstructor()
            ->getMock();

        $repositoriesApi->method('getRepoDetails')
            ->willReturn(new \stdClass());

        $repositoryMapperMock = $this->getMockBuilder(RepositoryMapper::class)
            ->getMock();
        $repositoryMapperMock->method('map')
            ->willReturn(new Repository());

        $githubService = new GithubRepositoriesService($repositoriesApi);
        $githubService->setRepositoryMapper($repositoryMapperMock);

        $result = $githubService->compare('owner/repo1', 'owner/repo2');

        $this->assertInternalType('array', $result);
    }

}