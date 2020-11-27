<?php
declare(strict_types=1);

namespace SpotifyTest\HttpApi\Controller;

use Psr\Http\Message\ResponseInterface;
use SpotifyTest\Application\Response\ApiResponse;

class ServiceController extends ApiController
{
    public function health(): ResponseInterface
    {
        return new ApiResponse([
            "isHealthy" => true,
            "status" => 200,
            "env" => $this->container->get('config')['app']['env']
        ]);
    }
}