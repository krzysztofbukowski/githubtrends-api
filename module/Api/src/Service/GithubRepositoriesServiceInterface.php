<?php
/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */

namespace Api\Service;


interface GithubRepositoriesServiceInterface
{
    public function compare(string $repository1, string $repository2);
}