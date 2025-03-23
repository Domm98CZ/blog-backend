<?php declare(strict_types = 1);
namespace Tests\Unit;

use App\Business\Enum\UserRole;
use App\Business\Interface\UserInterface;
use App\Business\Security\UserAccessControl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class UserAclTest extends TestCase
{
    private UserInterface $adminUser;
    private UserInterface $authorUser;
    private UserInterface $readerUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = new class implements UserInterface {
            public function getRole(): UserRole {
                return UserRole::ADMIN;
            }

            public function getId(): int {
                return 1;
            }
        };

        $this->authorUser = new class implements UserInterface {
            public function getRole(): UserRole {
                return UserRole::AUTHOR;
            }

            public function getId(): int {
                return 2;
            }
        };

        $this->readerUser = new class implements UserInterface {
            public function getRole(): UserRole {
                return UserRole::READER;
            }

            public function getId(): int {
                return 3;
            }
        };
    }

    #[Test]
    public function testAdminUserAcl(): void
    {
        $this->assertTrue(UserAccessControl::canReadUser($this->adminUser));
        $this->assertTrue(UserAccessControl::canReadUsers($this->adminUser));
        $this->assertTrue(UserAccessControl::canCreateUser($this->adminUser));
        $this->assertTrue(UserAccessControl::canUpdateUser($this->adminUser));
        $this->assertTrue(UserAccessControl::canDeleteUser($this->adminUser));
    }

    #[Test]
    public function testAuthorUserAcl(): void
    {
        $this->assertFalse(UserAccessControl::canReadUser($this->authorUser));
        $this->assertFalse(UserAccessControl::canReadUsers($this->authorUser));
        $this->assertFalse(UserAccessControl::canCreateUser($this->authorUser));
        $this->assertFalse(UserAccessControl::canUpdateUser($this->authorUser));
        $this->assertFalse(UserAccessControl::canDeleteUser($this->authorUser));
    }

    #[Test]
    public function testReaderUserAcl(): void
    {
        $this->assertFalse(UserAccessControl::canReadUser($this->readerUser));
        $this->assertFalse(UserAccessControl::canReadUsers($this->readerUser));
        $this->assertFalse(UserAccessControl::canCreateUser($this->readerUser));
        $this->assertFalse(UserAccessControl::canUpdateUser($this->readerUser));
        $this->assertFalse(UserAccessControl::canDeleteUser($this->readerUser));
    }

}
