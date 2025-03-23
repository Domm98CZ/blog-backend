<?php declare(strict_types = 1);
namespace Tests\Integration;

use App\Business\Controller\ArticleController;
use App\Business\Controller\UserController;
use App\Business\Exception\InsufficientPermissionsException;
use App\Data\Article\Article;
use App\Data\User\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Bootstrap;

class ArticleIntegrationTest extends TestCase
{
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
    public function testGetArticleByAdmin(): void
    {
        $articleEntity = $this->articleController->getArticle($this->adminUser, 1);
        $this->assertInstanceOf(Article::class, $articleEntity);
    }

    #[Test]
    public function testGetArticleByAuthor(): void
    {
        $articleEntity = $this->articleController->getArticle($this->authorUser, 1);
        $this->assertInstanceOf(Article::class, $articleEntity);
    }

    #[Test]
    public function testGetUserByReader(): void
    {
        $articleEntity = $this->articleController->getArticle($this->readerUser, 1);
        $this->assertInstanceOf(Article::class, $articleEntity);
    }

    #[Test]
    public function testUpdateMyArticleAdmin(): void
    {
        $articleEntity = $this->articleController->getArticle($this->adminUser, 1);
        $this->assertInstanceOf(Article::class, $articleEntity);

        $articleEntityNew = $this->articleController->updateArticle($this->adminUser, $articleEntity->id, title: 'Edited article');
        $this->assertInstanceOf(Article::class, $articleEntityNew);
        $this->assertEquals($articleEntity->id, $articleEntityNew->id);
        $this->assertEquals('Edited article', $articleEntityNew->title);
    }

    #[Test]
    public function testUpdateNotMyArticleAdmin(): void
    {
        $articleEntity = $this->articleController->getArticle($this->adminUser, 2);
        $this->assertInstanceOf(Article::class, $articleEntity);

        $articleEntityNew = $this->articleController->updateArticle($this->adminUser, $articleEntity->id, title: 'Edited article');
        $this->assertInstanceOf(Article::class, $articleEntityNew);
        $this->assertEquals($articleEntity->id, $articleEntityNew->id);
        $this->assertEquals('Edited article', $articleEntityNew->title);
    }

    #[Test]
    public function testUpdateMyArticleAuthor(): void
    {
        $articleEntity = $this->articleController->getArticle($this->authorUser, 2);
        $this->assertInstanceOf(Article::class, $articleEntity);

        $articleEntityNew = $this->articleController->updateArticle($this->authorUser, $articleEntity->id, title: 'Author edited article');
        $this->assertInstanceOf(Article::class, $articleEntityNew);
        $this->assertEquals($articleEntity->id, $articleEntityNew->id);
        $this->assertEquals('Author edited article', $articleEntityNew->title);
    }

    #[Test]
    public function testUpdateNotMyArticleAuthor(): void
    {
        $articleEntity = $this->articleController->getArticle($this->authorUser, 1);
        $this->assertInstanceOf(Article::class, $articleEntity);

        $this->expectException(InsufficientPermissionsException::class);
        $this->articleController->updateArticle($this->authorUser, $articleEntity->id, title: 'Author edited article');
    }

    #[Test]
    public function testUpdateArticleReader(): void
    {
        $articleEntity = $this->articleController->getArticle($this->readerUser, 1);
        $this->assertInstanceOf(Article::class, $articleEntity);

        $this->expectException(InsufficientPermissionsException::class);
        $this->articleController->updateArticle($this->readerUser, $articleEntity->id, title: 'Reader edited article');
    }

    #[Test]
    public function testAdminArticleWorkflow(): void
    {
        $articleEntity = $this->articleController->creteArticle($this->adminUser, 'Admin test article', 'Lorem ipsum dolor sit amet');
        $this->assertInstanceOf(Article::class, $articleEntity);


        $articleEntityNew = $this->articleController->updateArticle($this->adminUser, $articleEntity->id, title: 'Admin edited article');
        $this->assertInstanceOf(Article::class, $articleEntity);
        $this->assertInstanceOf(Article::class, $articleEntityNew);
        $this->assertEquals($articleEntity->id, $articleEntityNew->id);
        $this->assertEquals('Admin edited article', $articleEntityNew->title);

        $articleRemove = $this->articleController->deleteArticle($this->adminUser, $articleEntity->id);
        $this->assertTrue($articleRemove);
    }

    #[Test]
    public function testAuthorArticleWorkflow(): void
    {
        $articleEntity = $this->articleController->creteArticle($this->authorUser, 'Author test article', 'Lorem ipsum dolor sit amet');
        $this->assertInstanceOf(Article::class, $articleEntity);


        $articleEntityNew = $this->articleController->updateArticle($this->authorUser, $articleEntity->id, title: 'Author edited article');
        $this->assertInstanceOf(Article::class, $articleEntity);
        $this->assertInstanceOf(Article::class, $articleEntityNew);
        $this->assertEquals($articleEntity->id, $articleEntityNew->id);
        $this->assertEquals('Author edited article', $articleEntityNew->title);

        $articleRemove = $this->articleController->deleteArticle($this->authorUser, $articleEntity->id);
        $this->assertTrue($articleRemove);
    }

    #[Test]
    public function testReaderArticleWorkflow(): void
    {
        $this->expectException(InsufficientPermissionsException::class);

        $articleEntity = $this->articleController->creteArticle($this->readerUser, 'Reader test article', 'Lorem ipsum dolor sit amet');
        $this->articleController->updateArticle($this->readerUser, $articleEntity->id, title: 'Reader edited article');
        $this->articleController->deleteArticle($this->readerUser, $articleEntity->id);
    }
}

