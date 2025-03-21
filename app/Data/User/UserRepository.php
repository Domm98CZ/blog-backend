<?php declare(strict_types=1);
namespace App\Data\User;

use App\Data\AbstractRepository;

final class UserRepository extends AbstractRepository
{
    public static function getEntityClassNames(): array
    {
        return [User::class];
    }
}
