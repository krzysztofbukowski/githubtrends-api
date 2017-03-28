<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Service;

interface GithubServiceInterface
{
    /**
     * Get details about two repositories
     *
     * @param string $repositoryName1
     * @param string $repositoryName2
     *
     * @return mixed
     */
    public function compare(string $repositoryName1, string $repositoryName2);


    /**
     * Check if a repository exists
     *
     * @param string $repository
     *
     * @return boolean
     */
    public function checkIfRepoExists(string $repository);

    /**
     * Get the number of open pull requests
     *
     * @param string $owner
     * @param string $repository
     *
     * @return mixed
     */
    public function getOpenPullRequests(string $owner, string $repository);

    /**
     * Get the number of merged pull requests
     *
     * @param string $owner
     * @param string $repository
     *
     * @return mixed
     */
    public function getMergedPullRequests(string $owner, string $repository);


    /**
     * Get the number of closed pull requests
     *
     * @param string $owner
     * @param string $repository
     *
     * @return mixed
     */
    public function getClosedPullRequests(string $owner, string $repository);

    /**
     * Get the latest release in the given repository
     *
     * @param string $owner
     * @param string $repository
     *
     * @return mixed
     */
    public function getLatestRelease(string $owner, string $repository);

    /**
     * G
     *
     * @param $repository
     * @param $owner
     *
     * @return mixed
     */
    public function getRepo($repository, $owner);

}