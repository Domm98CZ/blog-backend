<?php declare(strict_types = 1);
namespace App\Business\Interface;

use App\Data\AbstractEntity;
use App\Data\RestApiDto\Request\AbstractRequestDto;
use App\Data\RestApiDto\Response\AbstractResponseDto;

interface RestApiEntity
{
    public static function createResponseDtoByEntity(AbstractEntity $entity, bool $loadRelations = false): AbstractResponseDto;
    public static function modifyEntityWithRequestDto(AbstractEntity $entity, AbstractRequestDto $requestDto): AbstractEntity;
}
