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
        list($owner, $repository) = explode('/', $repository1);

        $result1 = $this->_api->getRepoDetails($owner, $repository);

        list($owner, $repository) = explode('/', $repository2);

        $result2 = $this->_api->getRepoDetails($owner, $repository);

        return [
            $result1 ? $this->_mapper->map($result1)->getIterator()->getArrayCopy() : null,
            $result2 ? $this->_mapper->map($result2)->getIterator()->getArrayCopy() : null
        ];
    }
}