<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Service;

interface GithubServiceInterface
{
    public function compare(string $repositoryName1, string $repositoryName2);
}