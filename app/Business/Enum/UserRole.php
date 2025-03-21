<?php declare(strict_types=1);
namespace App\Business\Enum;

enum UserRole: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case READER = 'reader';
}
