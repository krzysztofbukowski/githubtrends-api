<?php


namespace ApiTest\Controller;

use Api\Controller\CompareController;
use Api\Module;
use Api\Service\GithubService;
use Zend\Log\Logger;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CompareControllerTest extends AbstractHttpControllerTestCase
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
            GithubService::class
        )->disableOriginalConstructor()->getMock();

        $githubRepositoriesServiceMock->expects($this->once())
            ->method('compare')
            ->willReturn([]);

        $loggerMock = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()->getMock();

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(
            GithubService::class,
            $githubRepositoriesServiceMock
        );
        $serviceManager->setService('logger', $loggerMock);

        $this->dispatch('/compare/owner/repo1,owner2/repo2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName(Module::NAME);
        $this->assertControllerName(CompareController::class); // as specified in router's controller name alias
        $this->assertControllerClass('comparecontroller');
        $this->assertMatchedRouteName('compare');
    }
}
