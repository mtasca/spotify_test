<?php
declare(strict_types=1);

namespace SpotifyTest\Application\Handlers;

use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;

class HttpErrorHandler extends SlimErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;
        $statusCode = 500;

        if ($exception instanceof HttpException) {
            $error = $exception->getDescription();
        } else {
            $error = $exception->getMessage();
        }

        $this->logger->log(Logger::ERROR, $error, $exception->getTrace());

        $payload = [
            'error' => $error,
        ];
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
