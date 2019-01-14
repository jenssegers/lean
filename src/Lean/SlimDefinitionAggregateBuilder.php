<?php

namespace Jenssegers\Lean;

use League\Container\Definition\DefinitionAggregate;
use League\Container\Definition\DefinitionAggregateInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\CallableResolver;
use Slim\Handlers\Error;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\PhpError;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Interfaces\RouterInterface;
use Slim\Router;
use Slim\Settings;

class SlimDefinitionAggregateBuilder
{
    /**
     * @var array
     */
    protected static $aliases = [
        Settings::class => 'settings',
        Request::class => 'request',
        RequestInterface::class => 'request',
        ServerRequestInterface::class => 'request',
        Response::class => 'response',
        ResponseInterface::class => 'response',
        Router::class => 'router',
        RouterInterface::class => 'router',
    ];

    /**
     * @var array
     */
    protected static $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        'addContentLengthHeader' => true,
        'routerCacheFile' => false,
        'methodInjection' => true,
    ];

    public static function build(ContainerInterface $container): DefinitionAggregateInterface
    {
        $aggregate = new DefinitionAggregate();
        $aggregate->setContainer($container);

        $aggregate->add('settings', function () {
            return new Settings(self::$defaultSettings);
        }, true);

        $aggregate->add('environment', function () {
            return new Environment($_SERVER);
        }, true);

        $aggregate->add('request', function () use ($container) {
            return Request::createFromEnvironment($container->get('environment'));
        }, true);

        $aggregate->add('response', function () use ($container) {
            $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
            $response = new Response(200, $headers);

            return $response->withProtocolVersion($container->get('settings')['httpVersion']);
        }, true);

        $aggregate->add('router', function () use ($container) {
            $routerCacheFile = false;
            if (isset($container->get('settings')['routerCacheFile'])) {
                $routerCacheFile = $container->get('settings')['routerCacheFile'];
            }

            $router = (new Router())->setCacheFile($routerCacheFile);
            if (method_exists($router, 'setContainer')) {
                $router->setContainer($container);
            }

            return $router;
        }, true);

        $aggregate->add('foundHandler', function () use ($container) {
            if ($container->get('settings')['methodInjection']) {
                return new MethodInjection($container);
            }

            return new RequestResponse();
        }, true);

        $aggregate->add('phpErrorHandler', function () use ($container) {
            return new PhpError($container->get('settings')['displayErrorDetails']);
        }, true);

        $aggregate->add('errorHandler', function () use ($container) {
            return new Error($container->get('settings')['displayErrorDetails']);
        }, true);

        $aggregate->add('notFoundHandler', function () {
            return new NotFound();
        }, true);

        $aggregate->add('notAllowedHandler', function () {
            return new NotAllowed();
        }, true);

        $aggregate->add('callableResolver', function () use ($container) {
            return new CallableResolver($container);
        }, true);

        // Register aliases.
        foreach (self::$aliases as $alias => $definition) {
            $aggregate->add($alias, function () use ($container, $definition) {
                return $container->get($definition);
            }, true);
        }

        return $aggregate;
    }
}
