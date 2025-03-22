<?php declare(strict_types=1);
namespace App\Presentation\Api\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final readonly class CorsMiddleware extends AbstractMiddleWare
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        if ($request->getMethod() === 'OPTIONS') {
            return $this->decorate($response);
        }

        /** @var ResponseInterface $response */
        $response = $next($request, $response);
        return $this->decorate($response);
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    private function decorate(ResponseInterface $response): ResponseInterface
    {
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Allow-Methods', '*')
            ->withHeader('Access-Control-Allow-Headers', '*');
    }
}
