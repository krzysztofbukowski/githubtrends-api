<?php

namespace Github;

use Zend\Http\Request;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class Client
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
     * @param string $path
     *
     * @return string
     */
    public function get(string $path)
    {
        $this->_httpClient->setMethod(Request::METHOD_GET);
        $this->_httpClient->setUri(self::API_HOST . $path);

        try {
            $response = $this->_httpClient->send();
            return json_decode($response->getBody());
        } catch (\Exception $e) {
            return null;
        }
    }
}