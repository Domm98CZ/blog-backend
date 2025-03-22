<?php declare(strict_types=1);
namespace App\Presentation\Api\Controllers;

use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Negotiation;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RequestParameter;
use Apitte\Core\Annotation\Controller\Response;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\OpenApi\ISchemaBuilder;
use Nette\Http\IRequest;

#[Path('/')]
#[Tag('Docs')]
final class DocsController extends BaseController
{
    /**
     * @param ISchemaBuilder $schemaBuilder
     */
    public function __construct(
        private readonly ISchemaBuilder $schemaBuilder)
    {

    }

    #[Path('/{name}')]
    #[Method(IRequest::Get)]
    #[RequestParameter('name', 'string', 'path', true, false, true, 'swagger.json')]
    #[Negotiation('json', true)]
    #[Response('Success - json', '200')]
    #[Response('Internal server error', '500')]
    public function index(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        $openApi = $this->schemaBuilder->build();
        $response->withHeader('Access-Control-Allow-Origin', '*');
        $openApiArray = $openApi->toArray();
        unset($openApiArray['paths']['/api/{name}']);
        return $response->writeJsonBody($openApiArray);
    }

}
