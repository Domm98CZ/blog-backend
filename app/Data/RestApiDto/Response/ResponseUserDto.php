<?php declare(strict_types=1);
namespace App\Data\RestApiDto\Response;

use App\Data\Article\Article;
use Nextras\Dbal\Utils\DateTimeImmutable;

final class ResponseUserDto extends AbstractResponseDto
{
    /**
     * @param int $id
     * @param string $email
     * @param string $name
     * @param string $role
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable $updatedAt
     * @param Article[] $articles
     */
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly string $name,
        public readonly string $role,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt,
        public array $articles = []
    ) {
    }
}
