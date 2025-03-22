<?php declare(strict_types=1);
namespace App\Business\Security;

use App\Business\Enum\UserRole;
use App\Data\User\User;

final readonly class UserAccessControl extends AbstractAccessControl
{
    /**
     * @param User $currentUser
     * @return bool
     */
    public static function canReadUser(User $currentUser): bool
    {
        return $currentUser->role === UserRole::ADMIN;
    }

    /**
     * @param User $currentUser
     * @return bool
     */
    public static function canReadUsers(User $currentUser): bool
    {
        return $currentUser->role === UserRole::ADMIN;
    }

    /**
     * @param User $currentUser
     * @return bool
     */
    public static function canCreateUser(User $currentUser): bool
    {
        return $currentUser->role === UserRole::ADMIN;
    }

    /**
     * @param User $currentUser
     * @return bool
     */
    public static function canUpdateUser(User $currentUser): bool
    {
        return $currentUser->role === UserRole::ADMIN;
    }

    /**
     * @param User $currentUser
     * @return bool
     */
    public static function canDeleteUser(User $currentUser): bool
    {
        return $currentUser->role === UserRole::ADMIN;
    }
}
