<?php

namespace Test\Model\Mapper;

use Api\Model\Mapper\RepositoryMapper;
use Api\Model\Repository;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class RepositoryMapperTest extends \PHPUnit_Framework_TestCase
{

    public function testMapReturnsRepositoryObject()
    {
        $mapper = new RepositoryMapper();
        $result = $mapper->map([
            'forks_count' => 1,
            'stargazers_count' => 1,
            'watchers_count' => 1,
            'full_name' => 'owner/repo'
        ]);

        $this->assertInstanceOf(Repository::class, $result);
    }
}