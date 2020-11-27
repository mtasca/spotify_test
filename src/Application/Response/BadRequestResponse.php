<?php
declare(strict_types=1);

namespace SpotifyTest\Application\Response;

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

class BadRequestResponse extends ApiResponse
{
    public function __construct(array $data = [], int $status = StatusCodeInterface::STATUS_BAD_REQUEST, array $headers = [])
    {
        parent::__construct($data, $status, $headers);
    }
}