<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 28/03/2017
 */

namespace Api\Service;


use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Log\Logger;

interface ServiceInterface
{

    /**
     * Get the cache adapter
     *
     * @return AbstractAdapter
     */
    public function getCacheAdapter();

    /**
     * Set the cache adapter
     *
     * @param AbstractAdapter $cacheAdapter
     *
     * @return mixed
     */
    public function setCacheAdapter(AbstractAdapter $cacheAdapter);

    /**
     * Set the logger instance
     *
     * @param Logger $logger
     * @return mixed
     */
    public function setLogger(Logger $logger);

    /**
     * Get the logger instance
     *
     * @return Logger
     */
    public function getLogger();
}