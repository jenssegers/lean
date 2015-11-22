<?php namespace Jenssegers\Lean;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Slim\CallableResolver;
use Slim\Collection;
use Slim\Handlers\Error;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;

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
        'errorHandler',
        'notFoundHandler',
        'notAllowedHandler',
        'callableResolver',
    ];

    /**
     * @var array
     */
    private $defaultSettings = [
        'httpVersion'                       => '1.1',
        'responseChunkSize'                 => 4096,
        'outputBuffering'                   => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails'               => false,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->getContainer()->share('settings', function () {
            return new Collection($this->defaultSettings);
        });

        $this->getContainer()->share('environment', function () {
            return new Environment($_SERVER);
        });

        $this->getContainer()->share('request', function () {
            return Request::createFromEnvironment($this->getContainer()->get('environment'));
        });

        $this->getContainer()->share('response', function () {
            $headers = new Headers(['Content-Type' => 'text/html']);
            $response = new Response(200, $headers);

            return $response->withProtocolVersion($this->getContainer()->get('settings')['httpVersion']);
        });

        $this->getContainer()->share('router', function () {
            return new Router;
        });

        $this->getContainer()->share('foundHandler', function () {
            return new RequestResponse;
        });

        $this->getContainer()->share('errorHandler', function () {
            return new Error($this->getContainer()->get('settings')['displayErrorDetails']);
        });

        $this->getContainer()->share('notFoundHandler', function () {
            return new NotFound;
        });

        $this->getContainer()->share('notAllowedHandler', function () {
            return new NotAllowed;
        });

        $this->getContainer()->share('callableResolver', function () {
            return new CallableResolver($this->getContainer());
        });
    }
}
