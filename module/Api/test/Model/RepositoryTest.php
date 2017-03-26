<?php

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class RepositoryTest extends PHPUnit_Framework_TestCase
{

    public function testGetIteratorReturnsArrayIterator()
    {
        $repository = new \Api\Model\Repository();
        $result = $repository->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $result);
    }

    public function testGetIteratorFillsValuesAccordingly()
    {
        $repository = new \Api\Model\Repository();
        $repository->setFullName('repository');
        $repository->setWatchers(1234);
        $repository->setStars(1234);
        $repository->setForks(1234);

        $result = $repository->getIterator();
        $this->assertArrayHasKey('forks', $result);
        $this->assertArrayHasKey('watchers', $result);
        $this->assertArrayHasKey('stars', $result);
        $this->assertArrayHasKey('full_name', $result);
    }


}