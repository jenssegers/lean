<?php

namespace Jenssegers\Lean\Tests;

use DateTime;
use Jenssegers\Lean\MethodInjection;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Slim\Http\Request;
use Slim\Http\Response;

class MethodInjectionTest extends TestCase
{
    public function testItSupportsMethodInjection()
    {
        $container = new Container();
        $strategy = new MethodInjection($container);

        // Add a concrete instance to the container.
        $yesterday = new DateTime('yesterday');
        $container->add(DateTime::class, $yesterday);

        $request = $this->createMock(Request::class);
        $response = $this->createMock(Response::class);

        $callable = function (DateTime $day, $foo) use ($yesterday) {
            $this->assertEquals($day, $yesterday);
            $this->assertEquals('bar', $foo);
        };

        $strategy($callable, $request, $response, ['foo' => 'bar']);
    }
}
