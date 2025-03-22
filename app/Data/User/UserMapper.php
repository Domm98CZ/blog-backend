<?php declare(strict_types=1);
namespace App\Data\User;

use App\Data\AbstractMapper;
use Nextras\Orm\Collection\ICollection;
use Nextras\Orm\Mapper\Dbal\Conventions\IConventions;

final class UserMapper extends AbstractMapper
{
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'users';
    }

    /**
     * @return IConventions
     */
    public function createConventions(): IConventions
    {
        $conventions = parent::createConventions();
        $conventions->setMapping('password_hash', 'password_hash');
        $conventions->setMapping('created_at', 'created_at');
        $conventions->setMapping('updated_at', 'updated_at');
        return $conventions;
    }
}
