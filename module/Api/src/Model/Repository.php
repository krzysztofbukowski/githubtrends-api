<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Model;

class Repository implements \IteratorAggregate
{

    protected $_forks;

    protected $_stars;

    protected $_watchers;

    protected $_fullName;

    protected $_latestRelease;

    protected $_openPullRequestsCount;

    protected $_closedPullRequestsCount;

    protected $_lastMergedPullRequestDate;

    /**
     * @return mixed
     */
    public function getLastMergedPullRequestDate()
    {
        return $this->_lastMergedPullRequestDate;
    }

    /**
     * @param mixed $lastMergedPullRequestDate
     */
    public function setLastMergedPullRequestDate($lastMergedPullRequestDate)
    {
        $this->_lastMergedPullRequestDate = $lastMergedPullRequestDate;
    }

    /**
     * @return mixed
     */
    public function getOpenPullRequestsCount()
    {
        return $this->_openPullRequestsCount;
    }

    /**
     * @param int $openPullRequestsCount
     */
    public function setOpenPullRequestsCount(int $openPullRequestsCount)
    {
        $this->_openPullRequestsCount = $openPullRequestsCount;
    }

    /**
     * @return mixed
     */
    public function getClosedPullRequestsCount()
    {
        return $this->_closedPullRequestsCount;
    }

    /**
     * @param mixed $closedPullRequestsCount
     */
    public function setClosedPullRequestsCount(int $closedPullRequestsCount)
    {
        $this->_closedPullRequestsCount = $closedPullRequestsCount;
    }


    /**
     * @return string
     */
    public function getLatestRelease()
    {
        return $this->_latestRelease;
    }

    /**
     * @param string $latestRelease
     */
    public function setLatestRelease(string $latestRelease)
    {
        $this->_latestRelease = $latestRelease;
    }

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


    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator([
            'forks' => $this->getForks(),
            'watchers' => $this->getWatchers(),
            'stars' => $this->getStars(),
            'full_name' => $this->getFullName(),
            'latest_release' => $this->getLatestRelease(),
            'open_pull_requests' => $this->getOpenPullRequestsCount(),
            'closed_pull_requests' => $this->getClosedPullRequestsCount(),
            'last_merged_pull_request' => $this->getLastMergedPullRequestDate(),
        ]);
    }
}