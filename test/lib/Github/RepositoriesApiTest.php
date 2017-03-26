<?php
namespace Test\Github;

use Github\Client;
use Github\RepositoriesApi;
use Zend\Http\Response;

/**
 * @author: Krzysztof Bukowski <ja@krzysztofbukowski.pl>
 * Date: 26/03/2017
 */
class RepositoriesApiTest extends \PHPUnit_Framework_TestCase
{

    public function testGetRepoDetailsWillUseGithubClient()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()->getMock();

        $client->method('get')
            ->with("/repos/$owner/$repository")
            ->willReturn(new Response());

        $repoApi = new RepositoriesApi($client);
        $repoApi->getRepoDetails($owner, $repository);
    }

    public function testGetRepoDetailsWillReturnObjectOnSuccess()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()->getMock();

        $client->method('get')
            ->with("/repos/$owner/$repository")
            ->willReturn(new \stdClass());

        $repoApi = new RepositoriesApi($client);
        $result = $repoApi->getRepoDetails($owner, $repository);

        $this->assertNotNull($result);
        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function testGetRepoDetailsWillReturnNullOnFailure()
    {
        $owner = 'phpunit';
        $repository = 'test';

        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()->getMock();

        $client->method('get')
            ->with("/repos/$owner/$repository")
            ->willReturn(null);

        $repoApi = new RepositoriesApi($client);
        $result = $repoApi->getRepoDetails($owner, $repository);

        $this->assertNull($result);
    }
}