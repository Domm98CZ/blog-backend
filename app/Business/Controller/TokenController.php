<?php declare(strict_types=1);

namespace App\Business\Controller;

use App\Business\Exception\EntityWasNotCreatedException;
use App\Business\Exception\EntityWasNotFoundException;
use App\Data\DataModel;
use App\Data\Token\Token;
use App\Data\Token\TokenRepository;
use App\Data\User\User;
use DateInterval;
use Exception;
use Nette\Utils\Random;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Tracy\Debugger;

final readonly class TokenController extends AbstractController
{
    // --- TOKEN SETTINGS
    private const int TOKEN_LENGTH = 32;
    private const string TOKEN_CHARLIST = '0-9a-zA-Z';
    public const string TOKEN_ALIVE = '24 hours';

    private TokenRepository $tokenRepository;

    public function __construct(
        DataModel $orm
    ) {
        $this->tokenRepository = $orm->tokenRepository;
    }

    /**
     * @param User $user
     * @return Token
     */
    public function createToken(User $user): Token
    {
        try {
            $existingToken = $this->tokenRepository->findTokenByUser($user);
            if ($existingToken instanceof Token && $existingToken->isValid()) {
                return $existingToken;
            }

            $tokenEntity = new Token();
            $tokenEntity->user = $user;
            $tokenEntity->is_revoked = false;
            $tokenEntity->token = Random::generate(self::TOKEN_LENGTH, self::TOKEN_CHARLIST);
            $tokenEntity->created_at = new DateTimeImmutable();
            $tokenEntity->updated_at = new DateTimeImmutable();
            $tokenEntity->last_used_at = new DateTimeImmutable();
            $tokenEntity->expires_at = new DateTimeImmutable()->add(DateInterval::createFromDateString(self::TOKEN_ALIVE));

            $this->tokenRepository->persist($tokenEntity);
            $this->tokenRepository->flush();
            return $tokenEntity;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityWasNotCreatedException(sprintf('TokenEntity was not created due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param string $token
     * @return Token
     */
    public function getToken(string $token): Token
    {
        $tokenEntity = $this->tokenRepository->findByToken($token);
        if (!$tokenEntity instanceof Token) {
            throw new EntityWasNotFoundException(sprintf('TokenEntity was not found by token "%s".', $token));
        }

        return $tokenEntity;
    }

    /**
     * @param Token $tokenEntity
     * @return void
     */
    public function touchToken(Token $tokenEntity): void
    {
        $tokenEntity->last_used_at = new DateTimeImmutable();
        $this->tokenRepository->persist($tokenEntity);
        $this->tokenRepository->flush();
    }
}
