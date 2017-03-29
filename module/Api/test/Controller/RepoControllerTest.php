<?php


namespace ApiTest\Controller;

use Api\Controller\RepoController;
use Api\Module;
use Api\Service\GithubService;
use Zend\Http\Response;
use Zend\Log\Logger;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class RepoControllerTest extends AbstractHttpControllerTestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_githubRepositoriesServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $_loggerMock;

    protected function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        $this->_githubRepositoriesServiceMock = $this->getMockBuilder(
            GithubService::class
        )->disableOriginalConstructor()->getMock();

        $this->_loggerMock = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()->getMock();

        parent::setUp();
    }

    public function testGetCanBeAccessed()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(GithubService::class, $this->_githubRepositoriesServiceMock);
        $serviceManager->setService('logger', $this->_loggerMock);

        $this->_githubRepositoriesServiceMock->expects($this->once())
            ->method('checkIfRepoExists')
            ->willReturn(true);

        $this->dispatch('/repo/owner/repo1', 'GET');
        $this->assertResponseStatusCode(Response::STATUS_CODE_200);
        $this->assertModuleName(Module::NAME);
        $this->assertControllerName(RepoController::class); // as specified in router's controller name alias
        $this->assertControllerClass('repocontroller');
        $this->assertMatchedRouteName('repository');
    }

    public function testGetShouldSetResponseCode404IfRepoNotFound()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService(GithubService::class, $this->_githubRepositoriesServiceMock);
        $serviceManager->setService('logger', $this->_loggerMock);

        $this->_githubRepositoriesServiceMock->expects($this->once())
            ->method('checkIfRepoExists')
            ->willReturn(false);

        $this->dispatch('/repo/owner/repo1', 'GET');
        $this->assertResponseStatusCode(Response::STATUS_CODE_404);
        $this->assertModuleName(Module::NAME);
        $this->assertControllerName(RepoController::class); // as specified in router's controller name alias
        $this->assertControllerClass('repocontroller');
        $this->assertMatchedRouteName('repository');

    }
}
