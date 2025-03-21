<?php declare(strict_types=1);
namespace App\Data\Token;

use App\Business\Enum\UserRole;
use App\Data\AbstractEntity;
use App\Data\User\User;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int                $id             {primary}
 * @property User               $user           {m:1 User::$tokens}
 * @property string             $token
 * @property bool               $is_revoked
 * @property UserRole           $role
 * @property DateTimeImmutable  $created_at
 * @property DateTimeImmutable  $updated_at
 * @property DateTimeImmutable  $last_used_at
 * @property DateTimeImmutable  $expires_at
 */
final class Token extends AbstractEntity
{

}
