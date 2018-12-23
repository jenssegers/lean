<?php

namespace Jenssegers\Lean;

use League\Container\Container;
use League\Container\ReflectionContainer;
use Slim\App as Slim;

class App extends Slim
{
    public function __construct(Container $container = null)
    {
        $container = $container ?: new Container();
        $container->delegate(new Container(SlimDefinitionAggregateBuilder::build($container)));
        $container->delegate(new ReflectionContainer());

        parent::__construct($container);
    }

    public function getContainer(): Container
    {
        return parent::getContainer();
    }
}
