<?php

namespace Api\Controller;

use Api\Module;
use Utils\Uptime;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        $uptime = new Uptime();

        $status = [
            'status' => Module::STATUS_RUNNING,
            'version' => Module::VERSION,
            'uptime' => $uptime->calculate(Uptime::HTTPD_PID)
        ];

        return new JsonModel($status);
    }
}
