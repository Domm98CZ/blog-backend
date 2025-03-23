<?php declare(strict_types=1);
namespace App\Business\Interface;

use App\Data\AbstractEntity;
use App\Data\RestApiDto\Response\AbstractResponseDto;

interface RestApiEntity
{
    public static function createResponseDtoByEntity(AbstractEntity $entity, bool $loadRelations = false): AbstractResponseDto;
}
