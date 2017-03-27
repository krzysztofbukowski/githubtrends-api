<?php

namespace Test\Model;

use Api\Model\Repository;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class RepositoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Api\Model\Repository
     */
    protected $_repository;

    protected function setUp()
    {
        $this->_repository = new Repository();
    }


    public function testGetIteratorReturnsArrayIterator()
    {
        $result = $this->_repository->getIterator();
        $this->assertInstanceOf(\ArrayIterator::class, $result);
    }

    public function testGetIteratorFillsValuesAccordingly()
    {
        $result = $this->_repository->getIterator();
        $this->assertInstanceOf(\ArrayIterator::class, $result);

        $this->assertArrayHasKey('forks', $result);
        $this->assertArrayHasKey('watchers', $result);
        $this->assertArrayHasKey('stars', $result);
        $this->assertArrayHasKey('full_name', $result);
        $this->assertArrayHasKey('latest_release', $result);
        $this->assertArrayHasKey('open_pull_requests', $result);
        $this->assertArrayHasKey('closed_pull_requests', $result);
        $this->assertArrayHasKey('last_merged_pull_request', $result);
    }

    public function testSetForksWorksProperly()
    {
        $this->_repository->setForks(100);
        $this->assertEquals(100, $this->_repository->getForks());
    }

    public function testSetStarsWorksProperly()
    {
        $this->_repository->setStars(100);
        $this->assertEquals(100, $this->_repository->getStars());
    }

    public function testSetWatchersWorksProperly()
    {
        $this->_repository->setWatchers(100);
        $this->assertEquals(100, $this->_repository->getWatchers());
    }

    public function testSetFullNameWorksProperly()
    {
        $this->_repository->setFullName("unit/test");
        $this->assertEquals("unit/test", $this->_repository->getFullName());
    }

    public function testSetLastReleaseWorksProperly()
    {
        $this->_repository->setLatestRelease("2017-01-01");
        $this->assertEquals("2017-01-01", $this->_repository->getLatestRelease());
    }

    public function testSetOpenPullRequestsCountWorksProperly()
    {
        $this->_repository->setOpenPullRequestsCount(100);
        $this->assertEquals(100, $this->_repository->getOpenPullRequestsCount());
    }

    public function testSetLastMergedPullRequestDateWorksProperly() {
        $this->_repository->setLastMergedPullRequestDate(100);
        $this->assertEquals(100, $this->_repository->getLastMergedPullRequestDate());
    }

    public function testSetClosedPullRequestsCountWorksProperly() {
        $this->_repository->setClosedPullRequestsCount(100);
        $this->assertEquals(100, $this->_repository->getClosedPullRequestsCount());
    }


}