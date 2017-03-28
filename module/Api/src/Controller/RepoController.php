<?php

namespace Api\Controller;

use Api\Service\GithubServiceInterface;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\View\View;

class RepoController extends AbstractRestfulController
{
    /**
     * @var GithubServiceInterface
     */
    protected $_service;

    public function __construct(GithubServiceInterface $service)
    {
        $this->_service = $service;
    }


    /**
     * Checks if a repository with the given name exists.
     * Returns 404 status code if the repository doesn't exist.
     *
     * @param string $id Repository name in the following format {owner}/{name}
     *
     * @return View
     */
    public function get($id)
    {
        $result = $this->_service->checkIfRepoExists($id);

        if ($result === false) {
            $this->getResponse()->setStatusCode(404);
        }

        return new View();
    }
}
