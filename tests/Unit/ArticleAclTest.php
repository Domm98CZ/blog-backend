<?php declare(strict_types = 1);
namespace Tests\Unit;

use App\Business\Enum\UserRole;
use App\Business\Interface\ArticleInterface;
use App\Business\Interface\UserInterface;
use App\Business\Security\ArticleAccessControl;
use App\Business\Security\UserAccessControl;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ArticleAclTest extends TestCase
{
    private UserInterface $adminUser;
    private UserInterface $authorUser;
    private UserInterface $readerUser;

    private ArticleInterface $adminArticle;
    private ArticleInterface $authorArticle;

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

        $this->adminArticle = new class implements ArticleInterface {
            public function getId(): int {
                return 1;
            }

            public function getAuthorId(): int {
                return 1;
            }
        };

        $this->authorArticle = new class implements ArticleInterface {
            public function getId(): int {
                return 2;
            }

            public function getAuthorId(): int {
                return 2;
            }
        };
    }

    #[Test]
    public function testAdminArticleAcl(): void
    {
        $this->assertTrue(ArticleAccessControl::canReadArticle($this->adminUser));
        $this->assertTrue(ArticleAccessControl::canReadArticles($this->adminUser));
        $this->assertTrue(ArticleAccessControl::canCreateArticle($this->adminUser));

        $this->assertTrue(ArticleAccessControl::canUpdateArticle($this->adminUser, $this->adminArticle));
        $this->assertTrue(ArticleAccessControl::canDeleteArticle($this->adminUser, $this->adminArticle));

        $this->assertTrue(ArticleAccessControl::canUpdateArticle($this->adminUser, $this->authorArticle));
        $this->assertTrue(ArticleAccessControl::canDeleteArticle($this->adminUser, $this->authorArticle));
    }

    #[Test]
    public function testAuthorArticleAcl(): void
    {
        $this->assertTrue(ArticleAccessControl::canReadArticle($this->authorUser));
        $this->assertTrue(ArticleAccessControl::canReadArticles($this->authorUser));
        $this->assertTrue(ArticleAccessControl::canCreateArticle($this->authorUser));

        $this->assertFalse(ArticleAccessControl::canUpdateArticle($this->authorUser, $this->adminArticle));
        $this->assertFalse(ArticleAccessControl::canDeleteArticle($this->authorUser, $this->adminArticle));

        $this->assertTrue(ArticleAccessControl::canUpdateArticle($this->authorUser, $this->authorArticle));
        $this->assertTrue(ArticleAccessControl::canDeleteArticle($this->authorUser, $this->authorArticle));
    }

    #[Test]
    public function testReaderArticleAcl(): void
    {
        $this->assertTrue(ArticleAccessControl::canReadArticle($this->readerUser));
        $this->assertTrue(ArticleAccessControl::canReadArticles($this->readerUser));
        $this->assertFalse(ArticleAccessControl::canCreateArticle($this->readerUser));

        $this->assertFalse(ArticleAccessControl::canUpdateArticle($this->readerUser, $this->adminArticle));
        $this->assertFalse(ArticleAccessControl::canDeleteArticle($this->readerUser, $this->adminArticle));

        $this->assertFalse(ArticleAccessControl::canUpdateArticle($this->readerUser, $this->authorArticle));
        $this->assertFalse(ArticleAccessControl::canDeleteArticle($this->readerUser, $this->authorArticle));
    }
}
