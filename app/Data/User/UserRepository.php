<?php declare(strict_types=1);
namespace App\Data\User;

use App\Data\AbstractRepository;
use Nextras\Orm\Collection\ICollection;

final class UserRepository extends AbstractRepository
{
    public static function getEntityClassNames(): array
    {
        return [User::class];
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return $this->findBy(['id' => $id])->fetch();
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findBy(['email' => $email])->fetch();
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function findUserByToken(string $token): ?User
    {
        return $this->findBy(['tokens->token' => $token])->fetch();
    }
}
