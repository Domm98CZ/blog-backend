<?php declare(strict_types=1);
namespace App\Presentation\Api\Controllers;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Negotiation;
use Apitte\Core\Annotation\Controller\OpenApi;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Response;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use App\Business\Controller\TokenController;
use App\Business\Exception\BusinessException;
use Nette\Http\IRequest;

#[Path('/auth')]
#[Tag('Auth')]
class AuthController extends BaseController
{
    public function __construct(
        private readonly \App\Business\Controller\UserController $userController,
        private readonly TokenController $tokenController,
    ) {

    }

    #[OpenApi('
        summary: Login via user account credentials to acquire temporary token.
    ')]
    #[Path('/login')]
    #[Method(IRequest::Post)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Internal server error', '500')]
    public function login(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            $data = $request->getJsonBody(true);
            if (isset($data['email']) && isset($data['password'])) {
                $userEntity = $this->userController->loginUser($data['email'], $data['password']);
                $tokenEntity = $this->tokenController->createToken($userEntity);
                return $this->success($response, ['token' => $tokenEntity->token, 'expires_at' => $tokenEntity->expires_at]);
            }
        } catch (BusinessException $exception) {
            return $this->failed($response, $exception->getMessage());
        }

        return $this->failed($response, 'Invalid email or password');

    }

    #[OpenApi('
        summary: User account register for blog.
    ')]
    #[Path('/register')]
    #[Method(IRequest::Post)]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Internal server error', '500')]
    public function register(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        try {
            $data = $request->getJsonBody(true);
            if (isset($data['email']) && isset($data['password']) && isset($data['name'])) {
                $userEntity = $this->userController->registerUser($data['email'], $data['password'], $data['name']);
                $tokenEntity = $this->tokenController->createToken($userEntity);
                return $this->success($response, ['token' => $tokenEntity->token, 'expires_at' => $tokenEntity->expires_at]);
            }
        } catch (BusinessException $exception) {
            return $this->failed($response, $exception->getMessage());
        }

        return $this->failed($response, 'Invalid email, password or name.');
    }
}
