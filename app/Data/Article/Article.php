<?php declare(strict_types=1);
namespace App\Data\Article;

use App\Business\Enum\UserRole;
use App\Data\AbstractEntity;
use App\Data\User\User;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int                $id             {primary}
 * @property string             $title
 * @property string             $content
 * @property User               $author         {m:1 User::$articles}
 * @property UserRole           $role
 * @property DateTimeImmutable  $created_at
 * @property DateTimeImmutable  $updated_at
 */
final class Article extends AbstractEntity
{

}
