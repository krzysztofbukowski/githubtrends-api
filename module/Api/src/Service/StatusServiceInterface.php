<?php

namespace Api\Service;

use Api\Model\Status;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
interface StatusServiceInterface
{
    /**
     * Returns information about the API service
     *
     * @return Status
     */
    public function getStatus() : Status;
}