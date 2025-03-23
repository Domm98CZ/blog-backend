<?php declare(strict_types = 1);
namespace Tests\Functional;

use App\Business\Controller\ArticleController;
use App\Business\Controller\UserController;
use App\Data\User\User;
use Domm98CZ\CurlClient\CurlClient;
use Nette\Utils\Json;
use Nette\Utils\Random;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Bootstrap;

class AuthApiTest extends TestCase
{
    private const string API_URL = 'http://localhost/api';

    private UserController $userController;
    private ArticleController $articleController;

    private User $adminUser;
    private User $authorUser;
    private User $readerUser;

    protected function setUp(): void
    {
        parent::setUp();

        $container = Bootstrap::createContainer();
        $this->userController = $container->getByType(UserController::class);
        $this->articleController = $container->getByType(ArticleController::class);

        $this->adminUser = $this->userController->loginUser('admin@domm.cz', 'demo1234');
        $this->authorUser = $this->userController->loginUser('author@domm.cz', 'demo1234');
        $this->readerUser = $this->userController->loginUser('reader@domm.cz', 'demo1234');
    }

    #[Test]
    public function testLogin(): void
    {
        $curlClient = new CurlClient();
        $curlClient->createRequest(self::API_URL . '/auth/login');
        $curlClient->setMethod(CurlClient::CURL_METHOD_POST);
        $curlClient->setSslVerifyHost(false);
        $curlClient->setSslVerifyPeer(false);
        $curlClient->setHeaders([
            'Content-Type:application/json'
        ]);
        $curlClient->setPostFields(Json::encode(['email' => 'admin@domm.cz', 'password' => 'demo1234']));
        $curlClient->execute();
        $response = $curlClient->getResponse();

        $this->assertTrue(is_string($response));
        $this->assertEquals(200, $curlClient->getHttpCode());

        $jsonArray = Json::decode($response, true);
        $token = $jsonArray['response']['message']['token'] ?? '';

        $this->assertNotEquals('', $token);
    }

    #[Test]
    public function testRegister(): void
    {
        $identity =Random::generate(8, '0-9a-z');
        $curlClient = new CurlClient();
        $curlClient->createRequest(self::API_URL . '/auth/register');
        $curlClient->setMethod(CurlClient::CURL_METHOD_POST);
        $curlClient->setSslVerifyHost(false);
        $curlClient->setSslVerifyPeer(false);
        $curlClient->setHeaders([
            'Content-Type:application/json'
        ]);
        $curlClient->setPostFields(Json::encode(['email' =>  $identity . '@domm.cz', 'password' => 'demo1234', 'name' => $identity]));
        $curlClient->execute();
        $response = $curlClient->getResponse();

        $this->assertTrue(is_string($response));
        $this->assertEquals(200, $curlClient->getHttpCode());

        $jsonArray = Json::decode($response, true);
        $token = $jsonArray['response']['message']['token'] ?? '';

        $this->assertNotEquals('', $token);
    }

    #[Test]
    public function testAuthorAddArticle(): void
    {
        $curlClient = new CurlClient();
        $curlClient->createRequest(self::API_URL . '/auth/login');
        $curlClient->setMethod(CurlClient::CURL_METHOD_POST);
        $curlClient->setSslVerifyHost(false);
        $curlClient->setSslVerifyPeer(false);
        $curlClient->setHeaders([
            'Content-Type:application/json'
        ]);
        $curlClient->setPostFields(Json::encode(['email' => 'author@domm.cz', 'password' => 'demo1234']));
        $curlClient->execute();
        $response = $curlClient->getResponse();

        $this->assertTrue(is_string($response));
        $this->assertEquals(200, $curlClient->getHttpCode());

        $jsonArray = Json::decode($response, true);
        $token = $jsonArray['response']['message']['token'] ?? '';

        $this->assertNotEquals('', $token);

        $curlClient = new CurlClient();
        $curlClient->createRequest(self::API_URL . '/articles');
        $curlClient->setMethod(CurlClient::CURL_METHOD_POST);
        $curlClient->setSslVerifyHost(false);
        $curlClient->setSslVerifyPeer(false);
        $curlClient->setHeaders([
            'Content-Type:application/json',
            'Authorization: Bearer ' . $token
        ]);
        $curlClient->setPostFields(Json::encode(['title' => 'API test article', 'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.']));
        $curlClient->execute();
        $response = $curlClient->getResponse();

        $this->assertTrue(is_string($response));
        $this->assertEquals(200, $curlClient->getHttpCode());

        $jsonArray = Json::decode($response, true);
        $newId = $jsonArray['response']['id'] ?? -1;
        $title = $jsonArray['response']['title'] ?? '';

        $this->assertNotEquals(-1, $newId);
        $this->assertNotEquals('', $title);
        $this->assertEquals('API test article', $title);
    }
}
