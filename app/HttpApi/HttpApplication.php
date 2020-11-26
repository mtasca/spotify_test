<?php
declare(strict_types=1);

namespace SpotifyTest\HttpApi;

use DI\ContainerBuilder;
use Monolog\Logger;
use Slim\Factory\AppFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\ResponseEmitter;
use Slim\Routing\RouteCollectorProxy;
use SpotifyTest\Application\Handlers\HttpErrorHandler;
use SpotifyTest\Application\Handlers\ShutdownHandler;
use SpotifyTest\Domain\Model\Environment\Environment;

class HttpApplication
{
    /**
     * @var \Slim\App
     */
    private $app;

    /**
     * @var Environment
     */
    private $env;

    /**
     * @var \DI\Container
     */
    private $container_builder;

    /**
     * @var HttpErrorHandler
     */
    private $error_handler;

    /**
     * @var ShutdownHandler
     */
    private $shutdown_handler;

    public function __construct()
    {
        $this->env = new Environment($_ENV['APP_ENV']);
        // Instantiate PHP-DI ContainerBuilder
        $this->container_builder = new ContainerBuilder();

        if (Environment::PROD == $this->env->getValue()) { // Should be set to true in production
            $this->container_builder->enableCompilation(__DIR__ . '/../var/cache');
        }

        $this->addConfigDefinitions();
        $this->addLogger();
        $this->addRepositories();

        // Build PHP-DI Container instance
        $this->container_builder = $this->container_builder->build();

        // Instantiate the app
        AppFactory::setContainer($this->container_builder);
        $this->app = AppFactory::create();

        // Register middleware
        $this->registerMiddlewares();

        // Register routes
        $this->registerRoutes();

        /** @var bool $displayErrorDetails */
        $displayErrorDetails = $this->container_builder->get('config')['app']['displayErrorDetails'];

        // Create Request object from globals
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();

        // Create Error Handler
        $this->error_handler = new HttpErrorHandler(
            $this->app->getCallableResolver(),
            $this->app->getResponseFactory(),
            $this->container_builder->get(LoggerInterface::class)
        );

        // Create Shutdown Handler
        $this->shutdown_handler = new ShutdownHandler($request, $this->error_handler, $displayErrorDetails);
        register_shutdown_function($this->shutdown_handler);

        // Add Routing Middleware
        $this->app->addRoutingMiddleware();

        // Add Error Middleware
        $errorMiddleware = $this->app->addErrorMiddleware($displayErrorDetails, false, false);
        $errorMiddleware->setDefaultErrorHandler($this->error_handler);

        // Run App & Emit Response
        $response = $this->app->handle($request);
        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);

    }

    private function addConfigDefinitions()
    {
        // TODO: improve the way to import the config files
        $app_config = require_once __DIR__ . "../../config/app.php";
        $logger_config = require_once __DIR__ . "../../config/logger.php";

        $this->container_builder->addDefinitions([
            'config' => [
                'app' => $app_config,
                'logger' => $logger_config,
            ]
        ]);
    }

    private function addLogger()
    {
        $this->container_builder->addDefinitions([
            LoggerInterface::class => function (ContainerInterface $c) {
                $config = $c->get('config');

                $logger_settings = $config['logger'];
                $logger = new Logger($logger_settings['name']);

                $processor = new UidProcessor();
                $logger->pushProcessor($processor);

                $handler = new StreamHandler(__DIR__ . $logger_settings['path'], $logger_settings['level']);
                $logger->pushHandler($handler);

                return $logger;
            },
        ]);
    }

    private function addRepositories()
    {
        //Add Repositories Here
    }

    private function registerMiddlewares()
    {
        //Add Middlewares here
    }

    private function registerRoutes()
    {
        $this->app->get(
            '/service/health',
            Controller\ServiceController::class . ':health'
        );

        // API v1 routes
        $this->app->group('/api/v1', function (RouteCollectorProxy $group) {
            //Add api v1 routes here
        });
    }
}