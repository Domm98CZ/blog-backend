<?php declare(strict_types=1);
namespace App\Data;

use App\Data\Article\ArticleRepository;
use App\Data\Token\TokenRepository;
use App\Data\User\UserRepository;
use Nextras\Orm\Model\Model;

/**
 * @property-read UserRepository        $userRepository
 * @property-read ArticleRepository     $articleRepository
 * @property-read TokenRepository       $tokenRepository
 */
final class DataModel extends Model
{

}
