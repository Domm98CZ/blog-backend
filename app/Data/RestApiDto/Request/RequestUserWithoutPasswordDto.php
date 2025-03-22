<?php declare(strict_types=1);
namespace App\Data\RestApiDto\Request;

final class RequestUserWithoutPasswordDto extends AbstractRequestDto
{
    public function __construct(
        public ?string $email = null,
        public ?string $name = null,
        public ?string $role = null
    ) {
    }
}
