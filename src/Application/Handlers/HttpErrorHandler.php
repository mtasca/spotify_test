<?php
declare(strict_types=1);

namespace SpotifyTest\Application\Handlers;

use SpotifyTest\Application\Actions\ActionError;
use SpotifyTest\Application\Actions\ActionPayload;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpNotImplementedException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Throwable;

class HttpErrorHandler extends SlimErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = 500;
        $error = '';

        if ($exception instanceof HttpException) {
            $error = $exception->getDescription();
        }else{
            $error = $exception->getMessage();
        }

        $payload = [
            'error' => $error,
        ];
        var_dump($exception->getTrace());die;
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
