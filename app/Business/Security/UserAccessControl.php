<?php declare(strict_types=1);
namespace App\Business\Security;

use App\Business\Enum\UserRole;
use App\Business\Interface\UserInterface;

final readonly class UserAccessControl extends AbstractAccessControl
{
    /**
     * @param UserInterface $currentUser
     * @return bool
     */
    public static function canReadUser(UserInterface $currentUser): bool
    {
        return $currentUser->getRole() === UserRole::ADMIN;
    }

    /**
     * @param UserInterface $currentUser
     * @return bool
     */
    public static function canReadUsers(UserInterface $currentUser): bool
    {
        return $currentUser->getRole() === UserRole::ADMIN;
    }

    /**
     * @param UserInterface $currentUser
     * @return bool
     */
    public static function canCreateUser(UserInterface $currentUser): bool
    {
        return $currentUser->getRole() === UserRole::ADMIN;
    }

    /**
     * @param UserInterface $currentUser
     * @return bool
     */
    public static function canUpdateUser(UserInterface $currentUser): bool
    {
        return $currentUser->getRole() === UserRole::ADMIN;
    }

    /**
     * @param UserInterface $currentUser
     * @return bool
     */
    public static function canDeleteUser(UserInterface $currentUser): bool
    {
        return $currentUser->getRole() === UserRole::ADMIN;
    }
}
