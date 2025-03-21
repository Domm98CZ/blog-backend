<?php declare(strict_types=1);
namespace App\Data\Token;

use App\Data\AbstractMapper;

final class TokenMapper extends AbstractMapper
{
    public function getTableName(): string
    {
        return 'auth_tokens';
    }
}
