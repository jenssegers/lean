<?php

namespace Jenssegers\Lean;

use League\Container\Container;
use League\Container\ReflectionContainer;
use Slim\App as Slim;

class App extends Slim
{
    /**
     * {@inheritdoc}
     */
    public function __construct(Container $container = null)
    {
        $container = $container ?: new Container;
        $container->delegate(new ReflectionContainer);
        $container->addServiceProvider(new SlimServiceProvider);

        parent::__construct($container);
    }
}
