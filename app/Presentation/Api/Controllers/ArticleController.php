<?php declare(strict_types=1);
namespace App\Presentation\Api\Controllers;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Negotiation;
use Apitte\Core\Annotation\Controller\OpenApi;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestBody;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\Response;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\Schema\EndpointParameter;
use App\Business\Enum\UserRole;
use App\Business\Exception\EntityListIsInvalidException;
use App\Business\Exception\EntityWasNotCreatedException;
use App\Business\Exception\EntityWasNotDeletedException;
use App\Business\Exception\EntityWasNotFoundException;
use App\Business\Exception\EntityWasNotUpdatedException;
use App\Business\Exception\InsufficientPermissionsException;
use App\Data\RestApiDto\Request\RequestArticleDto;
use App\Data\RestApiDto\Request\RequestUserDto;
use App\Data\RestApiDto\Request\RequestUserWithoutPasswordDto;
use App\Data\RestApiDto\Response\ResponseArticleDto;
use App\Data\User\User;
use App\Presentation\Api\RequestAttributes;
use Nette\Http\IRequest;
use Throwable;

#[Path('/articles')]
#[Tag('Articles')]
final class ArticleController extends BaseController
{
    public function __construct(
        private readonly \App\Business\Controller\ArticleController $articleController,
    ) {
    }

    #[OpenApi('
        summary: Get list of all articles in database.
    ')]
    #[Path('/')]
    #[Method(IRequest::Get)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Bad request', '400')]
    #[Response('Unauthorized', '401')]
    #[Response('Not found', '404')]
    #[Response('Not acceptable', '406')]
    #[Response('Internal server error', '500')]
    public function list(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            $collection = $this->articleController->getArticles($request->getAttribute(RequestAttributes::USER_ENTITY));
            $list = [];

            /** @var User $entity */
            foreach ($collection as $entity) {
                $list[$entity->id] = \App\Business\Controller\ArticleController::createResponseDtoByEntity($entity);
            }

            return $this->successEntityCollection($response, $list);
        } catch (InsufficientPermissionsException|EntityListIsInvalidException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }

    #[OpenApi('
        summary: Get detailed view of existing single article.
    ')]
    #[Path('/{id}')]
    #[RequestParameter('id', 'int', EndpointParameter::IN_PATH, true, false, true, 'User account ID')]
    #[Method(IRequest::Get)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200', ResponseArticleDto::class)]
    #[Response('Bad request', '400')]
    #[Response('Unauthorized', '401')]
    #[Response('Not found', '404')]
    #[Response('Not acceptable', '406')]
    #[Response('Internal server error', '500')]
    public function detail(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            return $this->successEntity(
                $response,
                \App\Business\Controller\ArticleController::createResponseDtoByEntity(
                    $this->articleController->getArticle($request->getAttribute(RequestAttributes::USER_ENTITY), intval($request->getParameter('id')))
                    , true
                )
            );
        } catch (InsufficientPermissionsException|EntityListIsInvalidException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }

    #[OpenApi('
        summary: Create new article..
    ')]
    #[Path('/')]
    #[Method(IRequest::Post)]
    #[RequestBody('ArticleEntityDto creation DTO.', RequestArticleDto::class, true, true)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200', ResponseArticleDto::class)]
    #[Response('Bad request', '400')]
    #[Response('Unauthorized', '401')]
    #[Response('Not found', '404')]
    #[Response('Not acceptable', '406')]
    #[Response('Internal server error', '500')]
    public function create(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            /** @var RequestArticleDto $dto */
            $dto = $request->getEntity();
            $entity = $this->articleController->creteArticle(
                $request->getAttribute(RequestAttributes::USER_ENTITY),
                $dto->title,
                $dto->content
            );

            return $this->successEntity($response, \App\Business\Controller\ArticleController::createResponseDtoByEntity($entity));
        } catch (InsufficientPermissionsException|EntityWasNotCreatedException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }

    #[OpenApi('
        summary: Update existing article.
    ')]
    #[Path('/{id}')]
    #[RequestParameter('id', 'int', EndpointParameter::IN_PATH, true, false, true, 'Article ID')]
    #[RequestBody('ArticleEntityDto update DTO.', RequestArticleDto::class, true, true)]
    #[Method(IRequest::Put)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Bad request', '400')]
    #[Response('Unauthorized', '401')]
    #[Response('Not found', '404')]
    #[Response('Not acceptable', '406')]
    #[Response('Internal server error', '500')]
    public function update(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            /** @var RequestArticleDto $dto */
            $dto = $request->getEntity();

            $entity = $this->articleController->updateArticle(
                $request->getAttribute(RequestAttributes::USER_ENTITY),
                intval($request->getParameter('id')),
                $dto->title,
                $dto->content,
            );

            return $this->successEntity($response, \App\Business\Controller\ArticleController::createResponseDtoByEntity($entity));
        } catch (InsufficientPermissionsException|EntityWasNotFoundException|EntityWasNotUpdatedException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }

    #[OpenApi('
        summary: Permanently delete existing article.
    ')]
    #[Path('/{id}')]
    #[RequestParameter('id', 'int', EndpointParameter::IN_PATH, true, false, true, 'User account ID')]
    #[Method(IRequest::Delete)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Bad request', '400')]
    #[Response('Unauthorized', '401')]
    #[Response('Not found', '404')]
    #[Response('Not acceptable', '406')]
    #[Response('Internal server error', '500')]
    public function delete(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            $id = intval($request->getParameter('id'));
            $result = $this->articleController->deleteArticle(
                $request->getAttribute(RequestAttributes::USER_ENTITY),
                $id
            );

            if ($result) {
                return $this->success($response, sprintf('ArticleEntity with id "%d" was removed.', $id));
            } else {
                return $this->failed($response, sprintf('ArticleEntity with id "%d" was not removed.', $id));
            }
        } catch (InsufficientPermissionsException|EntityWasNotFoundException|EntityWasNotDeletedException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }
}
