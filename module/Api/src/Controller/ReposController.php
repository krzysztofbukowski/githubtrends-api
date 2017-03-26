<?php

namespace Api\Controller;

use Api\Service\GithubRepositoriesServiceInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ReposController extends AbstractRestfulController
{
    /**
     * @var GithubRepositoriesServiceInterface
     */
    protected $_service;

    public function __construct(GithubRepositoriesServiceInterface $service)
    {
        $this->_service = $service;
    }


    public function get($id)
    {
        $repos = explode(',', $id);

        $repoDetails = $this->_service->compare($repos[0], $repos[1]);

        return new JsonModel(
            $repoDetails
        );
    }
}
