<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Service;


use Api\Model\Mapper\RepositoryMapper;
use Github\RepositoriesApi;

/**
 * Class GithubRepositoriesService
 *
 * @package Api\Service
 */
class GithubRepositoriesService implements GithubRepositoriesServiceInterface
{
    /**
     * @var RepositoriesApi
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
     * GithubRepositoriesService constructor.
     *
     * @param RepositoriesApi $api
     */
    public function __construct(RepositoriesApi $api)
    {
        $this->_api = $api;
    }

    /**
     * @param $mapper
     */
    public function setRepositoryMapper(RepositoryMapper $mapper)  {
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
    public function compare(string $repository1, string $repository2)
    {
        $result = null;
        $cacheKey = "repos_$repository1,$repository2";
        $cacheAdapter = $this->getCacheAdapter();

        if ($cacheAdapter instanceof \Zend\Cache\Storage\Adapter\AbstractAdapter) {
            $result = $cacheAdapter->getItem($cacheKey);
            if ($result != null) {
                return (array)json_decode($result);
            }
        }

        list($owner, $repository) = explode('/', $repository1);

        $repo1Details = $this->_api->getRepoDetails($owner, $repository);

        list($owner, $repository) = explode('/', $repository2);

        $repo2Details = $this->_api->getRepoDetails($owner, $repository);

        $result = [
            $repo1Details ? $this->_mapper->map($repo1Details)->getIterator()->getArrayCopy() : null,
            $repo2Details ? $this->_mapper->map($repo2Details)->getIterator()->getArrayCopy() : null
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
}