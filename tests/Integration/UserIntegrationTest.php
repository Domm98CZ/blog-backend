<?php declare(strict_types = 1);
namespace Tests\Integration;

use App\Business\Controller\UserController;
use App\Business\Enum\UserRole;
use App\Business\Exception\InsufficientPermissionsException;
use App\Data\User\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Bootstrap;

class UserIntegrationTest extends TestCase
{
    private UserController $userController;

    protected function setUp(): void
    {
        parent::setUp();

        $container = Bootstrap::createContainer();
        $this->userController = $container->getByType(UserController::class);
    }

    #[Test]
    public function testGetUserByAdmin(): void
    {
        $adminEntity = $this->userController->loginUser('admin@domm.cz', 'demo1234');
        $readerEntity = $this->userController->getUser($adminEntity, 3);
        $this->assertInstanceOf(User::class, $readerEntity);
        $this->assertEquals('reader@domm.cz', $readerEntity->email);
        $this->assertEquals(UserRole::READER, $readerEntity->role);
    }

    #[Test]
    public function testGetUserByAuthor(): void
    {
        $readerEntity = $this->userController->loginUser('author@domm.cz', 'demo1234');
        $this->expectException(InsufficientPermissionsException::class);
        $this->userController->getUser($readerEntity, 1);
    }

    #[Test]
    public function testGetUserByReader(): void
    {
        $readerEntity = $this->userController->loginUser('reader@domm.cz', 'demo1234');
        $this->expectException(InsufficientPermissionsException::class);
        $this->userController->getUser($readerEntity, 1);
    }

    #[Test]
    public function testUpdateUserByAdmin(): void
    {
        $adminEntity = $this->userController->loginUser('admin@domm.cz', 'demo1234');
        $authorEntity = $this->userController->updateUser($adminEntity, 2, name: 'Article author');
        $this->assertEquals('Article author', $authorEntity->name);
    }

    #[Test]
    public function testUpdateUserByAuthor(): void
    {
        $adminEntity = $this->userController->loginUser('author@domm.cz', 'demo1234');
        $this->expectException(InsufficientPermissionsException::class);
        $this->userController->updateUser($adminEntity, 2, name: 'Article author');
    }

    #[Test]
    public function testUpdateUserByReader(): void
    {
        $adminEntity = $this->userController->loginUser('reader@domm.cz', 'demo1234');
        $this->expectException(InsufficientPermissionsException::class);
        $this->userController->updateUser($adminEntity, 2, name: 'Article author');
    }
}
