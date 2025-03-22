<?php declare(strict_types=1);
namespace App\Presentation\Api;

interface RequestAttributes extends \Apitte\Core\Http\RequestAttributes
{
    public const string USER_ENTITY = 'userEntity';
}
