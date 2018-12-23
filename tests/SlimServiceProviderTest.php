<?php

namespace Jenssegers\Lean\Tests;

use Jenssegers\Lean\SlimServiceProvider;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

class SlimServiceProviderTest extends TestCase
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var SlimServiceProvider
     */
    private $provider;

    public function setUp()
    {
        $this->container = new Container();
        $this->provider = new SlimServiceProvider();
        $this->container->addServiceProvider($this->provider);
    }

    public function provideRequiredServices()
    {
        return [
            ['settings', \Slim\Settings::class],
            ['environment', \Slim\Http\Environment::class],
            ['request', \Slim\Http\Request::class],
            [\Slim\Http\Request::class, \Slim\Http\Request::class],
            ['response', \Slim\Http\Response::class],
            [\Slim\Http\Response::class, \Slim\Http\Response::class],
            ['router', \Slim\Router::class],
            ['foundHandler', \Slim\Interfaces\InvocationStrategyInterface::class],
            ['phpErrorHandler', \Slim\Handlers\PhpError::class],
            ['errorHandler', \Slim\Handlers\Error::class],
            ['notFoundHandler', \Slim\Handlers\NotFound::class],
            ['notAllowedHandler', \Slim\Handlers\NotAllowed::class],
            ['callableResolver', \Slim\CallableResolver::class],
        ];
    }

    /**
     * @dataProvider provideRequiredServices
     */
    public function testItRegistersSlimServices(string $containerKey, string $expectedClassName)
    {
        $this->assertTrue($this->container->has($containerKey));
        $this->assertInstanceOf($expectedClassName, $this->container->get($containerKey));
    }

    public function testHasSettings()
    {
        $this->assertTrue($this->container->has('settings'));
        $this->assertInstanceOf(\Slim\Collection::class, $this->container->get('settings'));
        $this->assertEquals(4096, $this->container->get('settings')->get('responseChunkSize'));
    }
}
