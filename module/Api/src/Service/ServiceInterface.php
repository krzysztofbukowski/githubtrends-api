<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 28/03/2017
 */

namespace Api\Service;


use Zend\Cache\Storage\Adapter\AbstractAdapter;

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
}