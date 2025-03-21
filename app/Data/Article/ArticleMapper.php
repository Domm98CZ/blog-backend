<?php declare(strict_types=1);
namespace App\Data\Article;

use App\Data\AbstractMapper;
use Nextras\Orm\Mapper\Dbal\Conventions\IConventions;

final class ArticleMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'articles';
    }

    /**
     * @return IConventions
     */
    public function createConventions(): IConventions
    {
        $conventions = parent::createConventions();
        $conventions->setMapping('created_at', 'created_at');
        $conventions->setMapping('updated_at', 'updated_at');
        return $conventions;
    }
}
