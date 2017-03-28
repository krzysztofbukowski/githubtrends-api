<?php

namespace Github;

use Zend\Http\Request;
use Zend\Http\Response;

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

    public function __construct(\Zend\Http\Client $httpClient)
    {
        $this->_httpClient = $httpClient;

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

        try {
            return $this->_httpClient->send();
        } catch (\Exception $e) {
            return null;
        }
    }
}