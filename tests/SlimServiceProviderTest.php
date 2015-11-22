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
        $this->assertInstanceOf('Slim\Collection', $this->container->get('settings'));
        $this->assertEquals(4096, $this->container->get('settings')->get('responseChunkSize'));
    }

    public function testHasEnvironment()
    {
        $this->assertTrue($this->container->has('environment'));
        $this->assertInstanceOf('Slim\Http\Environment', $this->container->get('environment'));
    }

    public function testHasRequest()
    {
        $this->assertTrue($this->container->has('request'));
        $this->assertInstanceOf('Slim\Http\Request', $this->container->get('request'));
    }

    public function testHasResponse()
    {
        $this->assertTrue($this->container->has('response'));
        $this->assertInstanceOf('Slim\Http\Response', $this->container->get('response'));
    }

    public function testHasRouter()
    {
        $this->assertTrue($this->container->has('router'));
        $this->assertInstanceOf('Slim\Router', $this->container->get('router'));
    }

    public function testHasFoundHandler()
    {
        $this->assertTrue($this->container->has('foundHandler'));
        $this->assertInstanceOf('Slim\Handlers\Strategies\RequestResponse', $this->container->get('foundHandler'));
    }

    public function testHasErrorHandler()
    {
        $this->assertTrue($this->container->has('errorHandler'));
        $this->assertInstanceOf('Slim\Handlers\Error', $this->container->get('errorHandler'));
    }

    public function testHasNotFoundHandler()
    {
        $this->assertTrue($this->container->has('notFoundHandler'));
        $this->assertInstanceOf('Slim\Handlers\NotFound', $this->container->get('notFoundHandler'));
    }

    public function testHasNotAllowedHandler()
    {
        $this->assertTrue($this->container->has('notAllowedHandler'));
        $this->assertInstanceOf('Slim\Handlers\NotAllowed', $this->container->get('notAllowedHandler'));
    }

    public function testHasCallableResolver()
    {
        $this->assertTrue($this->container->has('callableResolver'));
        $this->assertInstanceOf('Slim\CallableResolver', $this->container->get('callableResolver'));
    }
}
