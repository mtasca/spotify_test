<?php
declare(strict_types=1);

namespace SpotifyTest\Application\Handlers;

use Monolog\Logger;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;
use Slim\Interfaces\CallableResolverInterface;
use SpotifyTest\Domain\Model\Environment\Environment;

class HttpErrorHandler extends SlimErrorHandler
{
    private $env;

    public function __construct(Environment $env, CallableResolverInterface $callableResolver, ResponseFactoryInterface $responseFactory, ?LoggerInterface $logger = null)
    {
        $this->env = $env;
        parent::__construct($callableResolver, $responseFactory, $logger);
    }

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
            'error' => $error
        ];

        if($this->env->isDevelopment()) {
            $payload['trace'] = $exception->getTrace();
        }
        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);

        $response = $this->responseFactory->createResponse($statusCode);
        $response->getBody()->write($encodedPayload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}
