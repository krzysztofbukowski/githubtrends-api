<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Model;
use Traversable;

/**
 * Class Status
 *
 * Model class to define API status properties
 *
 * @package Api\Model
 */
class Status implements \IteratorAggregate
{

    /**
     * @var int How long has the API been running for in seconds
     */
    protected $_uptime;

    /**
     * @var string API version
     */
    protected $_version;

    /**
     * @return int
     */
    public function getUptime(): int
    {
        return $this->_uptime;
    }

    /**
     * @param int $uptime
     */
    public function setUptime(int $uptime)
    {
        $this->_uptime = $uptime;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->_version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->_version = $version;
    }

    /**
     * Status constructor.
     *
     * @param int    $uptime
     * @param string $version
     */
    public function __construct(int $uptime, string $version)
    {
        $this->setUptime($uptime);
        $this->setVersion($version);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getIterator()
    {
        return new \ArrayIterator([
            'uptime' => $this->getUptime(),
            'version' => $this->getVersion()
        ]);
    }
}