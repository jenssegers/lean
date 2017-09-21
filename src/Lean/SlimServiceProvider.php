<?php

namespace Jenssegers\Lean;

use Jenssegers\Lean\Strategies\AutoWiringStrategy;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\CallableResolver;
use Slim\Handlers\Error;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\PhpError;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Interfaces\RouterInterface;
use Slim\Router;
use Slim\Settings;

class SlimServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        'settings',
        'environment',
        'request',
        'response',
        'router',
        'foundHandler',
        'phpErrorHandler',
        'errorHandler',
        'notFoundHandler',
        'notAllowedHandler',
        'callableResolver',
    ];

    /**
     * @var array
     */
    protected $aliases = [
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
    protected $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        'addContentLengthHeader' => true,
        'routerCacheFile' => false,
    ];

    public function __construct()
    {
        // Add alias classes to the provides array.
        $this->provides = array_merge($this->provides, array_keys($this->aliases));
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->container->share('settings', function () {
            return new Settings($this->defaultSettings);
        });

        $this->container->share('environment', function () {
            return new Environment($_SERVER);
        });

        $this->container->share('request', function () {
            return Request::createFromEnvironment($this->container->get('environment'));
        });

        $this->container->share('response', function () {
            $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
            $response = new Response(200, $headers);

            return $response->withProtocolVersion($this->container->get('settings')['httpVersion']);
        });

        $this->container->share('router', function () {
            $routerCacheFile = false;
            if (isset($this->container->get('settings')['routerCacheFile'])) {
                $routerCacheFile = $this->container->get('settings')['routerCacheFile'];
            }

            $router = (new Router)->setCacheFile($routerCacheFile);
            if (method_exists($router, 'setContainer')) {
                $router->setContainer($this->container);
            }

            return $router;
        });

        $this->container->share('foundHandler', function () {
            return new AutoWiringStrategy($this->container);
        });

        $this->container->share('phpErrorHandler', function () {
            return new PhpError($this->container->get('settings')['displayErrorDetails']);
        });

        $this->container->share('errorHandler', function () {
            return new Error($this->container->get('settings')['displayErrorDetails']);
        });

        $this->container->share('notFoundHandler', function () {
            return new NotFound;
        });

        $this->container->share('notAllowedHandler', function () {
            return new NotAllowed;
        });

        $this->container->share('callableResolver', function () {
            return new CallableResolver($this->container);
        });

        // Register aliases.
        foreach ($this->aliases as $alias => $definition) {
            $this->container->share($alias, function () use ($definition) {
                return $this->container->get($definition);
            });
        }
    }
}
