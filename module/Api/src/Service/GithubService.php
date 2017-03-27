<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Service;


use Api\Model\Mapper\RepositoryMapper;
use Github\Api;
use Github\ApiInterface;
use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Http\Response;
use Zend\Log\Logger;

/**
 * Class GithubService
 *
 * @package Api\Service
 */
class GithubService implements GithubServiceInterface, ServiceInterface
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
     * @var Logger
     */
    protected $_logger;

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
    public function setCacheAdapter(AbstractAdapter $adapter)
    {
        $this->_cacheAdapter = $adapter;
    }

    /**
     * @return AbstractAdapter
     */
    public function getCacheAdapter()
    {
        return $this->_cacheAdapter;
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
            if ($this->getLogger() instanceof Logger) {
                $this->_logger->debug('Load data from cache', [$cacheKey, self::class]);
            }
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
            if ($this->getLogger() instanceof Logger) {
                $this->_logger->debug('Save data to cache', [$cacheKey, self::class]);
            }
            $cacheAdapter->setItem($cacheKey, json_encode($result));
        }

        return $result;
    }

    /**
     * @param $repository
     *
     * @return mixed|string
     */
    public function getRepoDetails($repository)
    {
        list($owner, $repository) = explode('/', $repository);

        $result = [];

        $result['details'] = $this->getRepo($repository, $owner);

        if ($result['details'] == null) {
            return null;
        }

        $result['latest_release'] = $this->getLatestRelease($owner, $repository);
        $result['pull_requests']['open'] = $this->getOpenPullRequests($owner, $repository);
        $result['pull_requests']['closed'] = $this->getClosedPullRequests($owner, $repository);
        $result['pull_requests']['merged'] = $this->getMergedPullRequests($owner, $repository);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestRelease(string $owner, string $repository)
    {
        return $this->_api->latest($owner, $repository);
    }

    /**
     * {@inheritdoc}
     */
    public function getOpenPullRequests(string $owner, string $repository)
    {
        return $this->_api->getPullRequests(
            $owner,
            $repository,
            ApiInterface::PR_IS_OPEN
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getMergedPullRequests(string $owner, string $repository)
    {
        return $this->_api->getPullRequests(
            $owner,
            $repository,
            ApiInterface::PR_IS_MERGED
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getClosedPullRequests(string $owner, string $repository)
    {
        return $this->_api->getPullRequests(
            $owner,
            $repository,
            ApiInterface::PR_IS_CLOSED
        );
    }

    /**
     * {@inheritdoc}
     */
    public function checkIfRepoExists(string $repository)
    {
        $keyName = "repo_$repository";
        $result = null;

        $cacheAdapter = $this->getCacheAdapter();

        if ($cacheAdapter instanceof AbstractAdapter) {
            $result = $cacheAdapter->getItem($keyName);
            if ($result == 'not_found') {
                return false;
            }
        }

        if ($result === null) {
            list($owner, $repository) = explode('/', $repository);

            $result = $this->_api->checkIfRepoExists($owner, $repository);

            if ($cacheAdapter instanceof AbstractAdapter) {
                $cacheAdapter->setItem($keyName, 'not_found');
            }
        }

        return $result;
    }

    /**
     * @param $repository
     * @param $owner
     *
     * @return mixed
     */
    public function getRepo($repository, $owner)
    {
        return $this->_api->get($owner, $repository);
    }


    /**
     * {@inheritdoc}
     */
    public function setLogger(Logger $logger)
    {
        $this->_logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger()
    {
        return $this->_logger;
    }
}