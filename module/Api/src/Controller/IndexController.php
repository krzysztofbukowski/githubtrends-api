<?php

namespace Api\Controller;

use Api\Service\StatusServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{
    /**
     * @var StatusServiceInterface
     */
    protected $_statusService;

    public function __construct(StatusServiceInterface $statusService)
    {
        $this->_statusService = $statusService;
    }


    public function indexAction()
    {
        return new JsonModel($this->_statusService->getStatus());
    }
}
