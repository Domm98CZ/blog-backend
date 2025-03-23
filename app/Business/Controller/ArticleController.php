<?php declare(strict_types=1);

namespace App\Business\Controller;

use App\Business\Exception\EntityListIsInvalidException;
use App\Business\Exception\EntityWasNotCreatedException;
use App\Business\Exception\EntityWasNotDeletedException;
use App\Business\Exception\EntityWasNotFoundException;
use App\Business\Exception\EntityWasNotUpdatedException;
use App\Business\Exception\InsufficientPermissionsException;
use App\Business\Interface\RestApiEntity;
use App\Business\Security\ArticleAccessControl;
use App\Data\AbstractEntity;
use App\Data\Article\Article;
use App\Data\Article\ArticleRepository;
use App\Data\DataModel;
use App\Data\RestApiDto\Response\ResponseArticleDto;
use App\Data\User\User;
use Exception;
use Nextras\Dbal\Utils\DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;
use Tracy\Debugger;

readonly class ArticleController extends AbstractController implements RestApiEntity
{
    private ArticleRepository $articleRepository;

    public function __construct(
        DataModel $orm
    ) {
        $this->articleRepository = $orm->articleRepository;
    }

    /**
     * @param User $currentUser
     * @param string $title
     * @param string $content
     * @return Article
     */
    public function creteArticle(User $currentUser, string $title, string $content): Article
    {
        try {
            if (!ArticleAccessControl::canCreateArticle($currentUser)) {
                throw new InsufficientPermissionsException('You do not have permission to create articles.');
            }

            $articleEntity = new Article();
            $articleEntity->title = $title;
            $articleEntity->content = $content;
            $articleEntity->author = $currentUser;
            $articleEntity->created_at = new DateTimeImmutable();
            $articleEntity->updated_at = new DateTimeImmutable();

            $this->articleRepository->persist($articleEntity);
            $this->articleRepository->flush();

            return $articleEntity;
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityWasNotCreatedException($exception->getMessage());
        }
    }

    /**
     * @param User $currentUser
     * @param int $id
     * @param string|null $title
     * @param string|null $content
     * @return Article
     */
    public function updateArticle(User $currentUser, int $id, ?string $title = null, ?string $content = null): Article
    {
        try {
            $articleEntity = $this->articleRepository->getById($id);
            if (!$articleEntity instanceof Article) {
                throw new EntityWasNotFoundException(sprintf('ArticleEntity with id "%s" does not exist.', $id));
            }

            if (!ArticleAccessControl::canUpdateArticle($currentUser, $articleEntity)) {
                throw new InsufficientPermissionsException('You do not have permission to update this article.');
            }

            if ($title !== null) {
                $articleEntity->title = $title;
            }

            if ($content !== null) {
                $articleEntity->content = $content;
            }

            if ($articleEntity->isModified()) {
                $articleEntity->updated_at = new DateTimeImmutable();
                $this->articleRepository->persist($articleEntity);
                $this->articleRepository->flush();
            }

            return $articleEntity;
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityWasNotUpdatedException(sprintf('ArticleEntity was not updated due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param User $currentUser
     * @param int $id
     * @return bool
     */
    public function deleteArticle(User $currentUser, int $id): bool
    {
        try {
            $articleEntity = $this->articleRepository->getById($id);
            if (!$articleEntity instanceof Article) {
                throw new EntityWasNotFoundException(sprintf('ArticleEntity with id "%s" does not exist.', $id));
            }

            if (!ArticleAccessControl::canDeleteArticle($currentUser, $articleEntity)) {
                throw new InsufficientPermissionsException('You do not have permission to delete articles.');
            }

            $this->articleRepository->removeAndFlush($articleEntity);
            return true;
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityWasNotDeletedException(sprintf('ArticleEntity was not deleted due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param User $currentUser
     * @param int $id
     * @return Article
     * @throws Exception
     */
    public function getArticle(User $currentUser, int $id): Article
    {
        try {
            if (!ArticleAccessControl::canReadArticle($currentUser)) {
                throw new InsufficientPermissionsException('You do not have permission to read this article.');
            }

            $articleEntity = $this->articleRepository->getById($id);
            if (!$articleEntity instanceof Article) {
                throw new EntityWasNotFoundException(sprintf('ArticleEntity with id "%s" does not exist.', $id));
            }

            return $articleEntity;
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new Exception(sprintf('ArticleEntity was not get due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param User $currentUser
     * @return ICollection<Article>
     */
    public function getArticles(User $currentUser): ICollection
    {
        try {
            if (!ArticleAccessControl::canReadArticles($currentUser)) {
                throw new InsufficientPermissionsException('You do not have permission to read articles.');
            }

            return $this->articleRepository->findAll();
        } catch (InsufficientPermissionsException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            Debugger::log($exception);
            throw new EntityListIsInvalidException(sprintf('ArticleEntityList was not get due to an exception "%s".', $exception->getMessage()));
        }
    }

    /**
     * @param AbstractEntity|Article $entity
     * @param bool $loadRelations
     * @return ResponseArticleDto
     */
    public static function createResponseDtoByEntity(AbstractEntity|Article $entity, bool $loadRelations = false): ResponseArticleDto
    {
        return new ResponseArticleDto(
            $entity->id,
            $entity->title,
            $entity->content,
            UserController::createResponseDtoByEntity($entity->author),
            $entity->created_at,
            $entity->updated_at
        );
    }
}
