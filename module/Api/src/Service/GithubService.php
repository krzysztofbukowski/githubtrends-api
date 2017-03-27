<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Service;


use Api\Model\Mapper\RepositoryMapper;
use Github\Api;
use Github\ApiInterface;

/**
 * Class GithubService
 *
 * @package Api\Service
 */
class GithubService implements GithubServiceInterface
{
    /**
     * @var Api
     */
    protected $_api;

    /**
     * @var
     */
    protected $_cacheAdapter;


    /**
     * @var RepositoryMapper
     */
    protected $_mapper;

    /**
     * GithubService constructor.
     *
     * @param Api $api
     */
    public function __construct(Api $api)
    {
        $this->_api = $api;
    }

    /**
     * @param $mapper
     */
    public function setRepositoryMapper(RepositoryMapper $mapper)
    {
        $this->_mapper = $mapper;
    }

    /**
     * @return RepositoryMapper
     */
    public function getRepositoryMapper()
    {
        return $this->_mapper;
    }


    /**
     * {@inheritdoc}
     */
    public function compare(string $repositoryName1, string $repositoryName2)
    {
        $result = null;
        $cacheKey = "repos_$repositoryName1,$repositoryName2";
        $cacheAdapter = $this->getCacheAdapter();

        if ($cacheAdapter instanceof \Zend\Cache\Storage\Adapter\AbstractAdapter) {
            $result = $cacheAdapter->getItem($cacheKey);
            if ($result != null) {
                return (array)json_decode($result);
            }
        }

        $repo1Details = $this->getRepoDetails($repositoryName1);
        $repo2Details = $this->getRepoDetails($repositoryName2);

        $result = [
            count($repo1Details) > 0 ? $this->_mapper->map($repo1Details)->getIterator()->getArrayCopy() : null,
            count($repo2Details) > 0 ? $this->_mapper->map($repo2Details)->getIterator()->getArrayCopy() : null
        ];

        if ($cacheAdapter instanceof \Zend\Cache\Storage\Adapter\AbstractAdapter) {
            $cacheAdapter->setItem($cacheKey, json_encode($result));
        }

        return $result;
    }


    /**
     * {@inheritdoc}
     */
    public function setCacheAdapter(\Zend\Cache\Storage\Adapter\AbstractAdapter $adapter)
    {
        $this->_cacheAdapter = $adapter;
    }

    /**
     * @return mixed
     */
    public function getCacheAdapter()
    {
        return $this->_cacheAdapter;
    }

    /**
     * @param $repository
     *
     * @return mixed|string
     */
    protected function getRepoDetails($repository)
    {
        list($owner, $repository) = explode('/', $repository);

        $result = [];

        if (($apiResult = $this->_api->get($owner, $repository)) !== null) {
            $result['details'] = $apiResult;
        }

        if (($apiResult = $this->_api->latest($owner, $repository)) !== null) {
            $result['latest_release'] = $apiResult;
        }

        if (($apiResult = $this->_api->getPullRequests(
                $owner,
                $repository,
                ApiInterface::PR_IS_OPEN
            )) !== null
        ) {
            $result['pull_requests']['open'] = $apiResult;
        }

        if (($apiResult = $this->_api->getPullRequests(
                $owner,
                $repository,
                ApiInterface::PR_IS_CLOSED
            )) !== null
        ) {
            $result['pull_requests']['closed'] = $apiResult;
        }

        if (($apiResult = $this->_api->getPullRequests(
                $owner,
                $repository,
                ApiInterface::PR_IS_MERGED
            )) !== null
        ) {
            $result['pull_requests']['merged'] = $apiResult;
        }

        return $result;
    }
}