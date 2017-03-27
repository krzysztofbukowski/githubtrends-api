<?php

namespace Api\Controller;

use Api\Service\GithubServiceInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ReposController extends AbstractRestfulController
{
    /**
     * @var GithubServiceInterface
     */
    protected $_service;

    public function __construct(GithubServiceInterface $service)
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
