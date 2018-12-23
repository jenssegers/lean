<?php

namespace Jenssegers\Lean;

use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Container\ServiceProvider\ServiceProviderAggregate;
use Slim\App as Slim;

class App extends Slim
{
    public function __construct(Container $container = null)
    {
        $container = $container ?: new Container();
        $container->delegate(new ReflectionContainer());

        $aggregate = new ServiceProviderAggregate();
        $aggregate->setContainer($container);
        $aggregate->add(new SlimServiceProvider());
        $container->delegate(new Container(null, $aggregate));

        parent::__construct($container);
    }

    public function getContainer(): Container
    {
        return parent::getContainer();
    }
}
