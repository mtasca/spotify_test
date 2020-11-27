<?php
declare(strict_types=1);

namespace SpotifyTest\HttpApi\Controller;

use Psr\Container\ContainerInterface;

abstract class ApiController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

}