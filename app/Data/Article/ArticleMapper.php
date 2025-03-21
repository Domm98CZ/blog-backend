<?php declare(strict_types=1);
namespace App\Data\Article;

use App\Data\AbstractMapper;

final class ArticleMapper extends AbstractMapper
{
    public function getTableName(): string
    {
        return 'articles';
    }
}
