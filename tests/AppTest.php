<?php

use Jenssegers\Lean\App;

class AppTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testLoadsServiceProvider()
    {
        $container = Mockery::mock(League\Container\Container::class);
        $container->shouldReceive('delegate')->once();
        $container->shouldReceive('addServiceProvider')->once()->with(Mockery::type(Jenssegers\Lean\SlimServiceProvider::class));

        $app = new App($container);
    }
}
