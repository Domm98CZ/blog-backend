<?php declare(strict_types=1);
namespace App\Data\RestApiDto\Response;

use Nextras\Dbal\Utils\DateTimeImmutable;

final class ResponseArticleDto extends AbstractResponseDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $content,
        public readonly ResponseUserDto $author,
        public readonly DateTimeImmutable $createdAt,
        public readonly DateTimeImmutable $updatedAt
    ) {
    }
}
