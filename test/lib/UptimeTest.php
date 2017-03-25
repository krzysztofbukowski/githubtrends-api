<?php
namespace Test\Utils;
use Utils\Uptime;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 25/03/2017
 */
class UptimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Uptime
     */
    protected $_uptime;


    protected function setUp()
    {
        $this->_uptime = new Uptime();

    }


    public function testCalculateReturnsInteger()
    {
        $path = tempnam(sys_get_temp_dir(), 'githubtrends');

        $result = $this->_uptime->calculate($path);
        $this->assertInternalType('int', $result);

        unlink($path);
    }
}