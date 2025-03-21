<?php declare(strict_types=1);
namespace App\Data\Token;

use App\Data\AbstractRepository;

final class TokenRepository extends AbstractRepository
{
    public static function getEntityClassNames(): array
    {
        return [Token::class];
    }
}
