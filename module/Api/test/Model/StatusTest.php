<?php

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class StatusTest extends PHPUnit_Framework_TestCase
{


    public function testGetIteratorReturnsArrayIterator()
    {
        $status = new \Api\Model\Status(
            100, "1.0"
        );
        $result = $status->getIterator();
        $this->assertInstanceOf(ArrayIterator::class, $result);
    }
}