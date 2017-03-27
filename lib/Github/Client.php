<?php

namespace Github;

use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Log\Logger;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class Client implements ClientInterface
{
    const API_HOST = 'https://api.github.com';

    /**
     * @var \Zend\Http\Client
     */
    protected $_httpClient;

    /**
     * @var \Zend\Log\Logger;
     */
    protected $_logger;

    public function __construct(\Zend\Http\Client $httpClient)
    {
        $this->_httpClient = $httpClient;

    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->_logger = $logger;
    }

    /**
     * @return Logger
     */
    public function getLogger() {
        return $this->_logger;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $path)
    {
        return $this->sendRequest($path, Request::METHOD_GET);
    }

    /**
     * {@inheritdoc}
     */
    public function head(string $path)
    {
        return $this->sendRequest($path, Request::METHOD_HEAD);
    }

    /**
     *
     * Sends a request to the given endpoint
     *
     * @param string $path
     * @param string $method
     *
     * @return Response|null
     */
    protected function sendRequest(string $path, string $method)
    {
        $this->_httpClient->setMethod($method);
        $this->_httpClient->setUri(self::API_HOST . $path);
        $logger = $this->getLogger();

        try {
            if ($logger instanceof Logger) {
                $this->_logger->debug("API REQUEST: $method: $path", [self::class]);
            }
            $result = $this->_httpClient->send();
            if ($logger instanceof Logger) {
                $this->_logger->debug("API RESPONSE:",
                    [
                        self::class,
                        $result->getStatusCode()
                    ]);
            }
            return $result;
        } catch (\Exception $e) {
            if ($logger instanceof Logger) {
                $this->_logger->debug($e->getMessage(), [self::class]);
            }
            return null;
        }
    }
}