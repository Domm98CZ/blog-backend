<?php declare(strict_types=1);
namespace App\Data\RestApiDto\Request;

final class RequestArticleDto extends AbstractRequestDto
{
    public function __construct(
        public ?string $title = null,
        public ?string $content = null
    ) {
    }
}
