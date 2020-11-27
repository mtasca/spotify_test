<?php
declare(strict_types=1);

namespace SpotifyTest\Application\Response;

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class ApiResponse extends Response
{
    public function __construct(array $data = [], int $status = StatusCodeInterface::STATUS_OK, array $headers = [])
    {
        $streamFactory = new StreamFactory();
        parent::__construct(
            $status,
            new Headers(array_merge($headers, ['Content-Type' => 'application/json'])),
            $streamFactory->createStream(json_encode(
                [
                    'metadata' => [
                        'code' => $status,
                        'message' => parent::$messages[$status]
                    ],
                    'data' => $data
                ]
            ))
        );
    }
}