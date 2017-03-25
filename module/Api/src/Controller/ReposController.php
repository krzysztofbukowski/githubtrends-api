<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ReposController extends AbstractRestfulController
{
    public function get($id)
    {
        return new JsonModel([$id]);
    }
}
