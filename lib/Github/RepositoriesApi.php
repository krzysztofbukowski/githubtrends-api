<?php

namespace Github;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class RepositoriesApi implements RepositoriesApiInterface, ApiInterface
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
    public function getRepoDetails(string $owner, string $repository)
    {
        return $this->_client->get("/repos/$owner/$repository");
    }
}