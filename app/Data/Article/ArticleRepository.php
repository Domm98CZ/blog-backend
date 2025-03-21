<?php declare(strict_types=1);
namespace App\Data\Article;

use App\Data\AbstractRepository;

final class ArticleRepository extends AbstractRepository
{
    public static function getEntityClassNames(): array
    {
        return [Article::class];
    }
}
