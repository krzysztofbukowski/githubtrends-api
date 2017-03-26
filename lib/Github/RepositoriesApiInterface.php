<?php

namespace Github;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
interface RepositoriesApiInterface
{

    /**
     * Get information about the given repository
     *
     * @param string $owner
     * @param string $repository
     *
     * @return mixed
     */
    public function getRepoDetails(string $owner, string $repository);
}