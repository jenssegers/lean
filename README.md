Lean
====

[![Latest Stable Version](http://img.shields.io/packagist/v/jenssegers/lean.svg)](https://packagist.org/packages/jenssegers/lean) [![Build Status](http://img.shields.io/travis/jenssegers/lean.svg)](https://travis-ci.org/jenssegers/lean) [![Coverage Status](http://img.shields.io/coveralls/jenssegers/lean.svg)](https://coveralls.io/r/jenssegers/lean)

Lean allows you to use the [PHP League's Container](https://github.com/thephpleague/container) package with auto-wiring support as the core container in [Slim 3](https://github.com/slimphp/Slim).

## Install

Via Composer

``` bash
$ composer require jenssegers/lean
```

## Usage

There are 2 ways you can start using Lean. The easiest way is to use the included `App` which extends the original Slim 3 `App` and injects the custom container:

``` php
require 'vendor/autoload.php';

$app = new \Jenssegers\Lean\App();

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    return $response->write('Hello, ' . $args['name']);
});

$app->run();
```

Behind the scenes the container is prepared using a `SlimServiceProvider` which bootstraps all of the required Slim components.

## Service Providers

Service providers give the benefit of organising your container definitions along with an increase in performance for larger applications as definitions registered within a service provider are lazily registered at the point where a service is retrieved.

To build a service provider it is as simple as extending the base service provider and defining what you would like to register.

```php
use League\Container\ServiceProvider\AbstractServiceProvider;

class SomeServiceProvider extends AbstractServiceProvider
{
    /**
     * The provided array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        'key',
        'Some\Controller',
        'Some\Model',
        'Some\Request'
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     */
    public function register()
    {
        $this->getContainer()->add('key', 'value');

        $this->getContainer()
            ->add('Some\Controller')
            ->addArgument('Some\Request')
            ->addArgument('Some\Model')
        ;
    }
}
```

To register this service provider with the container simply pass an instance of your provider or a fully qualified class name to the League\Container\Container::addServiceProvider method.

```php
$app = new \Jenssegers\Lean\App();
$app->getContainer()->addServiceProvider(\Acme\ServiceProvider\SomeServiceProvider::class);
```

Read more about service providers [here](https://container.thephpleague.com/3.x/service-providers/).

## Error Handlers

By default, Lean uses Slim's error handlers. If you want to overwrite error handler you can do that by simply adding your own error handler on the container like this:

```php
$app = new Jenssegers\Lean\App();

$app->getContainer()->share('errorHandler', function () {
    return new CustomErrorHandler();
});
```

## Usage with the original Slim application

If you prefer to use the original Slim application, you can bootstrap the entire process manually like this:

``` php
require 'vendor/autoload.php';

$container = new \League\Container\Container();
$container->delegate(new \League\Container\ReflectionContainer());
$container->addServiceProvider(new \Jenssegers\Lean\SlimServiceProvider());

$app = new \Slim\App($container);

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    return $response->write('Hello, ' . $args['name']);
});

$app->run();
```

## Testing

``` bash
$ php ./vendor/bin/phpunit
```

## License

The MIT License (MIT).
