<?php declare(strict_types=1);
namespace App\Data\User;

use App\Business\Enum\UserRole;
use App\Data\AbstractEntity;
use App\Data\Article\Article;
use App\Data\Token\Token;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Relationships\OneHasMany;

/**
 * @property int                    $id {primary}
 * @property string                 $email
 * @property string                 $password_hash
 * @property string                 $name
 * @property UserRole               $role
 * @property OneHasMany<Article>    $articles   {1:m Article::$author}
 * @property OneHasMany<Token>      $tokens     {1:m Token::$user}
 * @property DateTimeImmutable      $created_at
 * @property DateTimeImmutable      $updated_at
 */
final class User extends AbstractEntity
{

}
