<?php declare(strict_types=1);
namespace App\Business\Security;

use App\Business\Enum\UserRole;
use App\Business\Interface\ArticleInterface;
use App\Business\Interface\UserInterface;

final readonly class ArticleAccessControl extends AbstractAccessControl
{
    /**
     * @param UserInterface $currentUser
     * @return bool
     */
    public static function canReadArticle(UserInterface $currentUser): bool
    {
        return true;
    }

    /**
     * @param UserInterface $currentUser
     * @return bool
     */
    public static function canReadArticles(UserInterface $currentUser): bool
    {
        return true;
    }

    /**
     * @param UserInterface $currentUser
     * @return bool
     */
    public static function canCreateArticle(UserInterface $currentUser): bool
    {
        return in_array($currentUser->getRole(), [UserRole::ADMIN, UserRole::AUTHOR], true);
    }

    /**
     * @param UserInterface $currentUser
     * @param ArticleInterface $articleEntity
     * @return bool
     */
    public static function canUpdateArticle(UserInterface $currentUser, ArticleInterface $articleEntity): bool
    {
        if ($currentUser->getRole() === UserRole::AUTHOR && $articleEntity->getAuthorId() === $currentUser->getId()) {
            return true;
        }

        if ($currentUser->getRole() === UserRole::ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * @param UserInterface $currentUser
     * @param ArticleInterface $articleEntity
     * @return bool
     */
    public static function canDeleteArticle(UserInterface $currentUser, ArticleInterface $articleEntity): bool
    {
        if ($currentUser->getRole() === UserRole::AUTHOR && $articleEntity->getAuthorId() === $currentUser->getId()) {
            return true;
        }

        if ($currentUser->getRole() === UserRole::ADMIN) {
            return true;
        }

        return false;
    }
}
