<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Model;


use Traversable;

class Repository implements \IteratorAggregate
{

    protected $_forks;

    protected $_stars;

    protected $_watchers;

    protected $_fullName;

    /**
     * @return mixed
     */
    public function getForks()
    {
        return $this->_forks;
    }

    /**
     * @param mixed $forks
     */
    public function setForks($forks)
    {
        $this->_forks = $forks;
    }

    /**
     * @return mixed
     */
    public function getStars()
    {
        return $this->_stars;
    }

    /**
     * @param mixed $stars
     */
    public function setStars($stars)
    {
        $this->_stars = $stars;
    }

    /**
     * @return mixed
     */
    public function getWatchers()
    {
        return $this->_watchers;
    }

    /**
     * @param mixed $watchers
     */
    public function setWatchers($watchers)
    {
        $this->_watchers = $watchers;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->_fullName;
    }

    /**
     * @param mixed $name
     */
    public function setFullName($name)
    {
        $this->_fullName = $name;
    }


    public function getIterator()
    {
        return new \ArrayIterator([
            'forks' => $this->getForks(),
            'watchers' => $this->getWatchers(),
            'stars' => $this->getStars(),
            'full_name' => $this->getFullName(),
        ]);
    }
}