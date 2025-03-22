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
use App\Data\RestApiDto\Request\RequestUserDto;
use App\Data\RestApiDto\Request\RequestUserWithoutPasswordDto;
use App\Data\User\User;
use App\Presentation\Api\RequestAttributes;
use Nette\Http\IRequest;
use Throwable;

#[Path('/users')]
#[Tag('Users')]
final class UserController extends BaseController
{
    public function __construct(
        private readonly \App\Business\Controller\UserController $userController,
    ) {
    }

    #[OpenApi('
        summary: Get list of all users accounts in database.
    ')]
    #[Path('/')]
    #[Method(IRequest::Get)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Internal server error', '500')]
    public function list(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            $collection = $this->userController->getUsers($request->getAttribute(RequestAttributes::USER_ENTITY));
            $list = [];

            /** @var User $entity */
            foreach ($collection as $entity) {
                $list[$entity->id] = \App\Business\Controller\UserController::createResponseDtoByEntity($entity);
            }

            return $this->successEntityCollection($response, $list);
        } catch (InsufficientPermissionsException|EntityListIsInvalidException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }

    #[OpenApi('
        summary: Get detailed view of existing single user account.
    ')]
    #[Path('/{id}')]
    #[RequestParameter('id', 'int', EndpointParameter::IN_PATH, true, false, true, 'User account ID')]
    #[Method(IRequest::Get)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Internal server error', '500')]
    public function detail(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            return $this->successEntity(
                $response,
                \App\Business\Controller\UserController::createResponseDtoByEntity(
                    $this->userController->getUser($request->getAttribute(RequestAttributes::USER_ENTITY), intval($request->getParameter('id')))
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
        summary: Create new user account.
    ')]
    #[Path('/')]
    #[Method(IRequest::Post)]
    #[RequestBody('UserEntityDto creation DTO.', RequestUserDto::class, true, true)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Internal server error', '500')]
    public function create(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            /** @var RequestUserDto $dto */
            $dto = $request->getEntity();
            $entity = $this->userController->creteUser(
                $request->getAttribute(RequestAttributes::USER_ENTITY),
                $dto->email,
                $dto->password,
                $dto->name,
                UserRole::from($dto->role),
            );

            return $this->successEntity($response, \App\Business\Controller\UserController::createResponseDtoByEntity($entity));
        } catch (InsufficientPermissionsException|EntityWasNotCreatedException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }

    #[OpenApi('
        summary: Update existing user account.
    ')]
    #[Path('/{id}')]
    #[RequestParameter('id', 'int', EndpointParameter::IN_PATH, true, false, true, 'User account ID')]
    #[RequestBody('Data for update', RequestUserWithoutPasswordDto::class, true, true)]
    #[Method(IRequest::Put)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Internal server error', '500')]
    public function update(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            /** @var RequestUserDto $dto */
            $dto = $request->getEntity();

            $entity = $this->userController->updateUser(
                $request->getAttribute(RequestAttributes::USER_ENTITY),
                intval($request->getParameter('id')),
                $dto->email,
                $dto->name,
                UserRole::from($dto->role)
            );

            return $this->successEntity($response, \App\Business\Controller\UserController::createResponseDtoByEntity($entity));
        } catch (InsufficientPermissionsException|EntityWasNotFoundException|EntityWasNotUpdatedException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }

    #[OpenApi('
        summary: Permanently existing delete user.
    ')]
    #[Path('/{id}')]
    #[RequestParameter('id', 'int', EndpointParameter::IN_PATH, true, false, true, 'User account ID')]
    #[Method(IRequest::Delete)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Internal server error', '500')]
    public function delete(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            $id = intval($request->getParameter('id'));
            $result = $this->userController->deleteUser(
                $request->getAttribute(RequestAttributes::USER_ENTITY),
                $id
            );

            if ($result) {
                return $this->success($response, sprintf('UserEntity with id "%d" was removed.', $id));
            } else {
                return $this->failed($response, sprintf('UserEntity with id "%d" was not removed.', $id));
            }
        } catch (InsufficientPermissionsException|EntityWasNotFoundException|EntityWasNotDeletedException $exception) {
            return $this->failed($response, $exception->getMessage());
        } catch (Throwable $exception) {
            return $this->exception($response, $exception);
        }
    }
}
