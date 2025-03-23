<?php declare(strict_types=1);
namespace App\Data\Article;

use App\Business\Enum\UserRole;
use App\Business\Interface\ArticleInterface;
use App\Business\Interface\EntityInterface;
use App\Data\AbstractEntity;
use App\Data\User\User;
use JsonSerializable;
use Nextras\Dbal\Utils\DateTimeImmutable;

/**
 * @property int                $id             {primary}
 * @property string             $title
 * @property string             $content
 * @property User               $author         {m:1 User::$articles}
 * @property DateTimeImmutable  $created_at
 * @property DateTimeImmutable  $updated_at
 */
final class Article extends AbstractEntity implements ArticleInterface
{
    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->author->id;
    }
}
