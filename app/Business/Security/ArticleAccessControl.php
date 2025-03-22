<?php declare(strict_types=1);
namespace App\Business\Security;

use App\Business\Enum\UserRole;
use App\Data\Article\Article;
use App\Data\User\User;

final readonly class ArticleAccessControl extends AbstractAccessControl
{
    /**
     * @param User $currentUser
     * @return bool
     */
    public static function canReadArticle(User $currentUser): bool
    {
        return true;
    }

    /**
     * @param User $currentUser
     * @return bool
     */
    public static function canReadArticles(User $currentUser): bool
    {
        return true;
    }

    /**
     * @param User $currentUser
     * @return bool
     */
    public static function canCreateArticle(User $currentUser): bool
    {
        return in_array($currentUser->role, [UserRole::ADMIN, UserRole::AUTHOR], true);
    }

    /**
     * @param User $currentUser
     * @param Article $articleEntity
     * @return bool
     */
    public static function canUpdateArticle(User $currentUser, Article $articleEntity): bool
    {
        if ($currentUser->role === UserRole::AUTHOR && $articleEntity->author->id === $currentUser->id) {
            return true;
        }

        if ($currentUser->role === UserRole::ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * @param User $currentUser
     * @param Article $articleEntity
     * @return bool
     */
    public static function canDeleteArticle(User $currentUser, Article $articleEntity): bool
    {
        if ($currentUser->role === UserRole::AUTHOR && $articleEntity->author->id === $currentUser->id) {
            return true;
        }

        if ($currentUser->role === UserRole::ADMIN) {
            return true;
        }

        return false;    }
}
