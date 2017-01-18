<?php

use Jenssegers\Lean\SlimServiceProvider;
use League\Container\Container;

class SlimServiceProviderTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->container = new Container;
        $this->provider = new SlimServiceProvider;
        $this->container->addServiceProvider($this->provider);
    }

    public function testHasSettings()
    {
        $this->assertTrue($this->container->has('settings'));
        $this->assertInstanceOf(Slim\Collection::class, $this->container->get('settings'));
        $this->assertEquals(4096, $this->container->get('settings')->get('responseChunkSize'));
    }

    public function testHasEnvironment()
    {
        $this->assertTrue($this->container->has('environment'));
        $this->assertInstanceOf(Slim\Http\Environment::class, $this->container->get('environment'));
    }

    public function testHasRequest()
    {
        $this->assertTrue($this->container->has('request'));
        $this->assertInstanceOf(Slim\Http\Request::class, $this->container->get('request'));

        // Alias.
        $this->assertTrue($this->container->has(Slim\Http\Request::class));
        $this->assertInstanceOf(Slim\Http\Request::class, $this->container->get(Slim\Http\Request::class));
    }

    public function testHasResponse()
    {
        $this->assertTrue($this->container->has('response'));
        $this->assertInstanceOf(Slim\Http\Response::class, $this->container->get('response'));

        // Alias.
        $this->assertTrue($this->container->has(Slim\Http\Response::class));
        $this->assertInstanceOf(Slim\Http\Response::class, $this->container->get(Slim\Http\Response::class));
    }

    public function testHasRouter()
    {
        $this->assertTrue($this->container->has('router'));
        $this->assertInstanceOf(Slim\Router::class, $this->container->get('router'));
    }

    public function testHasFoundHandler()
    {
        $this->assertTrue($this->container->has('foundHandler'));
        $this->assertInstanceOf(Jenssegers\Lean\Strategies\AutoWiringStrategy::class,
            $this->container->get('foundHandler'));
    }

    public function testHasPhpErrorHandler()
    {
        $this->assertTrue($this->container->has('phpErrorHandler'));
        $this->assertInstanceOf(Slim\Handlers\PhpError::class, $this->container->get('phpErrorHandler'));
    }

    public function testHasErrorHandler()
    {
        $this->assertTrue($this->container->has('errorHandler'));
        $this->assertInstanceOf(Slim\Handlers\Error::class, $this->container->get('errorHandler'));
    }

    public function testHasNotFoundHandler()
    {
        $this->assertTrue($this->container->has('notFoundHandler'));
        $this->assertInstanceOf(Slim\Handlers\NotFound::class, $this->container->get('notFoundHandler'));
    }

    public function testHasNotAllowedHandler()
    {
        $this->assertTrue($this->container->has('notAllowedHandler'));
        $this->assertInstanceOf(Slim\Handlers\NotAllowed::class, $this->container->get('notAllowedHandler'));
    }

    public function testHasCallableResolver()
    {
        $this->assertTrue($this->container->has('callableResolver'));
        $this->assertInstanceOf(Slim\CallableResolver::class, $this->container->get('callableResolver'));
    }
}
