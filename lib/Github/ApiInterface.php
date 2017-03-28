<?php

namespace Github;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
interface ApiInterface
{
    const PR_IS_OPEN = "open";
    const PR_IS_CLOSED = "closed";
    const PR_IS_MERGED = "merged";

    /**
     * Set the github http client
     *
     * @param Client $client
     *
     * @return mixed
     */
    public function setClient(Client $client);

    /**
     * Get information about the given repository
     *
     * @param string $owner
     * @param string $repository
     *
     * @return mixed
     */
    public function get(string $owner, string $repository);

    /**
     * Get the latest release of the given repository
     *
     * @param string $owner
     * @param string $repository
     *
     * @return mixed
     */
    public function latest(string $owner, string $repository);


    /**
     * Get pull requests of the given repository
     *
     * @param string $owner
     * @param string $repository
     * @param string $is (open|closed|merged)
     *
     * @return mixed
     */
    public function getPullRequests(string $owner, string $repository, string $is);

    /**
     * Check if the given repository exists. Return true if yes, false otherwise
     *
     * @param string $owner
     * @param string $repository
     *
     *
     * @return boolean
     */
    public function checkIfRepoExists(string $owner, string $repository);
}