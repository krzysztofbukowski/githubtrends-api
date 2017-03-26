<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Service;


use Api\Model\Status;
use Api\Module;
use Utils\Uptime;

class StatusService implements StatusServiceInterface
{

    /**
     * @var Uptime
     */
    protected $_uptime;

    public function __construct(Uptime $uptime)
    {
        $this->_uptime = $uptime;
    }


    /**
     * {@inheritdoc}
     */
    public function getStatus(): Status
    {
        return new Status(
            $this->_uptime->calculate(Uptime::HTTPD_PID),
            Module::VERSION);
    }
}