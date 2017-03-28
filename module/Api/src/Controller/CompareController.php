<?php

namespace Api\Controller;

use Api\Service\GithubServiceInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class CompareController extends AbstractRestfulController
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
     *
     * Returns details about two given repositories
     *
     * @param string $id Repository names separated by a comma: repository1,repository2
     *
     * @return JsonModel
     */
    public function get($id)
    {
        $repos = explode(',', $id);

        $repoDetails = $this->_service->compare($repos[0], $repos[1]);

        return new JsonModel(
            $repoDetails
        );
    }
}
