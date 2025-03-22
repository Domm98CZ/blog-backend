<?php declare(strict_types = 1);
namespace App\Presentation\Api\Controllers;

use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\Tag;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\UI\Controller\IController;
use Apitte\Negotiation\Http\ArrayEntity;
use App\Data\RestApiDto\Response\AbstractResponseDto;
use Exception;
use Nette\Utils\DateTime;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Throwable;
use Tracy\Debugger;

#[Path('/api')]
#[Tag('API')]
abstract class BaseController implements IController
{
    public const float apiVersion = 1.0;

    /**
     * @param  array  $entity
     * @return ArrayEntity
     */
    private function response(array $entity): ArrayEntity
    {
        return ArrayEntity::from([
            'app' => [
                'datetime' => new DateTime(),
                'version' => self::apiVersion
            ]
            , 'response' => $entity
        ]);
    }

    /**
     * @param  ApiResponse  $response
     * @param  string       $message
     * @return ApiResponse
     */
    protected function notFound(ApiResponse $response, string $message): ApiResponse
    {
        return $response
            ->withStatus(ApiResponse::S404_NOT_FOUND)
            ->withEntity($this->response([
                'code' => ApiResponse::S404_NOT_FOUND,
                'status' => 'error',
                'message' => $message
            ]))
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param ApiResponse $response
     * @param array|string $message
     * @return ApiResponse
     */
    protected function failed(ApiResponse $response, array|string $message): ApiResponse
    {
        return $response
            ->withStatus(ApiResponse::S406_NOT_ACCEPTABLE)
            ->withEntity($this->response([
                'code' => ApiResponse::S406_NOT_ACCEPTABLE,
                'status' => 'error',
                'message' => $message
            ]))
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param ApiResponse $response
     * @param array|string $message
     * @return ApiResponse
     */
    protected function success(ApiResponse $response, array|string $message): ApiResponse
    {
        $formalResponse = [
            'code' => ApiResponse::S200_OK,
            'status' => 'success',
            'message' => $message
        ];

        return $response
            ->withStatus(ApiResponse::S200_OK)
            ->withEntity($this->response($formalResponse))
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param ApiResponse $response
     * @param AbstractResponseDto $dto
     * @return ApiResponse
     */
    protected function successEntity(ApiResponse $response, AbstractResponseDto $dto): ApiResponse
    {
        return $response
            ->withStatus(ApiResponse::S200_OK)
            ->withEntity($this->response($dto->toResponse()))
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param  ApiResponse              $response
     * @param  AbstractResponseDto[]    $dtoCollection
     * @return ApiResponse
     */
    protected function successEntityCollection(ApiResponse $response, array $dtoCollection): ApiResponse
    {
        return $response
            ->withStatus(ApiResponse::S200_OK)
            ->withEntity($this->response($dtoCollection))
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param ApiResponse $response
     * @param Throwable $exception
     * @return ApiResponse
     */
    protected function exception(ApiResponse $response, Throwable $exception): ApiResponse
    {
        Debugger::log($exception);
        return $response
            ->withStatus(ApiResponse::S500_INTERNAL_SERVER_ERROR)
            ->withEntity($this->response([
                'code' => ApiResponse::S500_INTERNAL_SERVER_ERROR,
                'status' => 'error',
                'message' => 'Application encountered an internal error, exception was logged. Please try again later.',
                'exception' => $exception->getMessage()
            ]))
            ->withHeader('Content-Type', 'application/json');
    }
}
