<?php declare(strict_types=1);
namespace App\Presentation\Api\Middlewares;

use Apitte\Core\Http\ApiResponse;
use App\Business\Controller\UserController;
use App\Business\Exception\InvalidLoginCredentialsException;
use App\Data\User\User;
use App\Presentation\Api\RequestAttributes;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class AuthenticationMiddleware extends AbstractMiddleWare
{
    private const string SWAGGER_PATH = '/api/swagger.json';

    public function __construct(
        private UserController $userController
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     * @throws JsonException
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if (str_starts_with($request->getUri()->getPath(), self::SWAGGER_PATH) || str_starts_with($request->getUri()->getPath(), '/api/auth')) {
            return $next($request, $response);
        }

        $token = null;
        $matches = [];
        $headers = $this->getAuthorizationHeader();

        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $token = $matches[1];
            }
        }

        if (empty($token)) {
            return $this->notAuthenticated($response, 'Bearer token not provided.');
        }

        $userEntity = null;
        try {
            $userEntity = $this->userController->authorizeUser($token);
            if (!$userEntity instanceof User) {
                return $this->notAuthenticated($response, 'Bearer token is invalid or expired.');
            }
        } catch (InvalidLoginCredentialsException) {
            return $this->notAuthenticated($response, 'Bearer token is invalid or expired.');
        }

        $request = $request->withAttribute(RequestAttributes::USER_ENTITY, $userEntity);
        return $next($request, $response);
    }


    /**
     * @param ResponseInterface $response
     * @param string $message
     * @return ResponseInterface
     * @throws JsonException
     */
    private function notAuthenticated(ResponseInterface $response, string $message): ResponseInterface
    {
        $response->getBody()->write(
            Json::encode([
                'code'    => ApiResponse::S401_UNAUTHORIZED,
                'status'  => 'error',
                'message' => $message,
            ])
        );

        return $response->withStatus(ApiResponse::S401_UNAUTHORIZED)
            ->withHeader('Content-Type', 'application/json')
        ;
    }

    /**
     * @return string|null
     */
    public static function getAuthorizationHeader(): ?string
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}
