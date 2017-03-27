<?php

namespace Github;

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
        return $this->_client->get("/search/issues?q=+type:pr+repo:$owner/$repository+is:$is");
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $owner, string $repository)
    {
        return $this->_client->get("/repos/$owner/$repository");
    }

    /**
     * {@inheritdoc}
     */
    public function latest(string $owner, string $repository)
    {
        return $this->_client->get("/repos/$owner/$repository/releases/latest");
    }
}