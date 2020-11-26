<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

/**
 * Depending of the implementation details of the production environment we can use or not the .env variables.
 * These lines are commented to avoid using the commited .env
 * TODO: Define production implementation details of the environment variables
 */
//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
//$dotenv->load();

$app = new \SpotifyTest\HttpApi\HttpApplication();