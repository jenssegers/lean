<?php

use Jenssegers\Lean\App;
use League\Container\Container;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testItLoadsTheSlimServiceProviderOnAnInjected()
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
}
