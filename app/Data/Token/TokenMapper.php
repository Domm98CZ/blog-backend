<?php declare(strict_types=1);
namespace App\Data\Token;

use App\Data\AbstractMapper;
use Nextras\Orm\Mapper\Dbal\Conventions\IConventions;

final class TokenMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'auth_tokens';
    }

    /**
     * @return IConventions
     */
    public function createConventions(): IConventions
    {
        $conventions = parent::createConventions();
        $conventions->setMapping('is_revoked', 'is_revoked');
        $conventions->setMapping('created_at', 'created_at');
        $conventions->setMapping('updated_at', 'updated_at');
        $conventions->setMapping('last_used_at', 'last_used_at');
        $conventions->setMapping('expires_at', 'expires_at');
        return $conventions;
    }
}
