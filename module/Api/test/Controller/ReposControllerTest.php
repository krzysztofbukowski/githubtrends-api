<?php


namespace ApiTest\Controller;

use Api\Controller\ReposController;
use Api\Module;
use Api\Service\GithubRepositoriesService;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class ReposControllerTest extends AbstractHttpControllerTestCase
{
    protected $_controller;
    protected $_request;
    protected $_response;
    protected $_routeMatch;
    protected $_event;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testGetCanBeAccessed()
    {

        $githubRepositoriesServiceMock = $this->getMockBuilder(
            GithubRepositoriesService::class
        )->disableOriginalConstructor()->getMock();

        $githubRepositoriesServiceMock->method('compare')
            ->willReturn([]);

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            GithubRepositoriesService::class,
            $githubRepositoriesServiceMock
        );


        $this->dispatch('/repos/owner/repo1,owner/repo2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName(Module::NAME);
        $this->assertControllerName(ReposController::class); // as specified in router's controller name alias
        $this->assertControllerClass('reposcontroller');
        $this->assertMatchedRouteName('repository/stats');
    }
}
