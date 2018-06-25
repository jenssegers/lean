<?php

use Jenssegers\Lean\Strategies\AutoWiringStrategy;
use League\Container\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class AutoWiringStrategyTest extends PHPUnit_Framework_TestCase
{
    public function testCallableAutoWiring()
    {
        $container = new Container();
        $strategy = new AutoWiringStrategy($container);

        $yesterday = new DateTime('yesterday');

        $container->add(Request::class, Mockery::mock(Request::class));
        $container->add(Response::class, Mockery::mock(Response::class));
        $container->add(DateTime::class, $yesterday);

        $callable = function (DateTime $day, $foo) use ($yesterday) {
            PHPUnit_Framework_Assert::assertTrue($day === $yesterday);
            PHPUnit_Framework_Assert::assertSame('bar', $foo);
        };

        $strategy($callable, $container->get(Request::class), $container->get(Response::class), ['foo' => 'bar']);
    }
}
