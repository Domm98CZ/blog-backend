<?php declare(strict_types=1);
namespace App\Data\Token;

use App\Data\AbstractRepository;
use App\Data\User\User;
use Nextras\Dbal\Utils\DateTimeImmutable;

final class TokenRepository extends AbstractRepository
{
    public static function getEntityClassNames(): array
    {
        return [Token::class];
    }

    /**
     * @param User $user
     * @return Token|null
     */
    public function findTokenByUser(User $user): ?Token
    {
        return $this->findBy([
            'user' => $user
            , 'expires_at>=' => new DateTimeImmutable()
        ])->fetch();
    }

    /**
     * @param string $token
     * @return Token|null
     */
    public function findByToken(string $token): ?Token
    {
        return $this->findBy(['token' => $token])->fetch();
    }
}
