<?php

namespace Jenssegers\Lean;

use League\Container\ReflectionContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;

class MethodInjection implements InvocationStrategyInterface
{
    /**
     * @var ContainerInterface
     */
    private $reflectionContainer;

    public function __construct(ContainerInterface $container)
    {
        $this->reflectionContainer = new ReflectionContainer();
        $this->reflectionContainer->setContainer($container);
    }

    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    ) {
        foreach ($routeArguments as $k => $v) {
            $request = $request->withAttribute($k, $v);
        }

        return $this->reflectionContainer->call($callable, $routeArguments);
    }
}
