<?php

namespace Jenssegers\Lean\Tests;

use Jenssegers\Lean\App;
use Jenssegers\Lean\Tests\Stubs\ErrorHandler;
use Jenssegers\Lean\Tests\Stubs\ErrorServiceProvider;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testItLoadsTheSlimServiceProviderWithACustomContainer()
    {
        $container = new Container();
        $app = new App($container);
        $this->assertTrue($container->has('request'));
    }

    public function testItConstructsWithoutAContainer()
    {
        $app = new App();
        $container = $app->getContainer();
        $this->assertTrue($container->has('request'));
    }

    public function testItSupportsOverwritingDefaultDefinitions()
    {
        $app = new App();
        $container = $app->getContainer();
        $this->assertTrue($container->has('errorHandler'));

        $container->share('errorHandler', function () {
            return new ErrorHandler();
        });

        $this->assertInstanceOf(ErrorHandler::class, $app->getContainer()->get('errorHandler'));
        $this->assertTrue($app->getContainer()->has('request'));
    }

    public function testItDoesntOverwriteAlreadyRegisteredDefinitions()
    {
        $container = new Container();
        $this->assertFalse($container->has('errorHandler'));

        $container->share('errorHandler', function () {
            return new ErrorHandler();
        });

        $app = new App($container);
        $this->assertInstanceOf(ErrorHandler::class, $app->getContainer()->get('errorHandler'));
        $this->assertTrue($app->getContainer()->has('request'));
    }

    public function testItSupportsPreInjectedServiceProviders()
    {
        $container = new Container();
        $container->addServiceProvider(ErrorServiceProvider::class);
        $app = new App($container);

        $this->assertInstanceOf(ErrorHandler::class, $app->getContainer()->get('errorHandler'));
        $this->assertTrue($app->getContainer()->has('request'));
    }

    public function testItSupportsPostInjectedServiceProviders()
    {
        $app = new App();
        $app->getContainer()->addServiceProvider(ErrorServiceProvider::class);

        $this->assertInstanceOf(ErrorHandler::class, $app->getContainer()->get('errorHandler'));
        $this->assertTrue($app->getContainer()->has('request'));
    }
}
