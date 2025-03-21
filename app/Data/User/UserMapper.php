<?php declare(strict_types=1);
namespace App\Data\User;

use App\Data\AbstractMapper;

final class UserMapper extends AbstractMapper
{
    public function getTableName(): string
    {
        return 'users';
    }
}
