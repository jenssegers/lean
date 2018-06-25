<?php

namespace Jenssegers\Lean;

use League\Container\Container;
use League\Container\ContainerInterface;
use League\Container\ReflectionContainer;
use Slim\App as Slim;

class App extends Slim
{
    /**
     * @inheritdoc
     */
    public function __construct(ContainerInterface $container = null)
    {
        $container = $container ?: new Container();
        $container->delegate(new ReflectionContainer());
        $container->addServiceProvider(new SlimServiceProvider());

        parent::__construct($container);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return parent::getContainer();
    }
}
