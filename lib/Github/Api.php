<?php

namespace Github;
use Zend\Http\Response;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class Api implements ApiInterface
{
    /**
     * @var Client;
     */
    protected $_client = null;


    /**
     * RepositoriesApi constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(Client $client)
    {
        $this->_client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function getPullRequests(string $owner, string $repository, string $is)
    {
        $result = $this->_client->get("/search/issues?q=+type:pr+repo:$owner/$repository+is:$is");

        if ($result instanceof Response
            && $result->getStatusCode() == Response::STATUS_CODE_200) {
            return json_decode($result->getBody());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $owner, string $repository)
    {
        $result = $this->_client->get("/repos/$owner/$repository");

        if ($result instanceof Response
            && $result->getStatusCode() == Response::STATUS_CODE_200) {
            return json_decode($result->getBody());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function latest(string $owner, string $repository)
    {
        $result = $this->_client->get("/repos/$owner/$repository/releases/latest");

        if ($result instanceof Response
            && $result->getStatusCode() == Response::STATUS_CODE_200) {
            return json_decode($result->getBody());
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function checkIfRepoExists(string $owner, string $repository)
    {
        $result = $this->_client->head("/repos/$owner/$repository");

        return $result instanceof Response &&
            $result->getStatusCode() == Response::STATUS_CODE_200;
    }
}