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

The easiest way to start using Lean is simply creating a `Jenssegers\Lean\App` instance:

``` php
require 'vendor/autoload.php';

$app = new \Jenssegers\Lean\App();

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    return $response->write('Hello, ' . $args['name']);
});

$app->run();
```

Behind the scenes a Slim application is bootstrapped using a `SlimServiceProvider` which adds all of the required Slim components to League's container.

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
     */
    protected $provides = [
        SomeInterface::class,
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     */
    public function register()
    {
        $this->getContainer()
            ->add(SomeInterface::class, SomeImplementation::class);
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

Ideally you would put this code inside a service provider. Read more about service providers above.

## Testing

``` bash
$ php ./vendor/bin/phpunit
```

## License

The MIT License (MIT).
