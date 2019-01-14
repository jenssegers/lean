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

$app->get('/hello/{name}', function (Request $request, Response $response, string $name) {
    return $response->write('Hello, ' . $name);
});

$app->run();
```

Behind the scenes, a Slim application is bootstrapped by adding all of the required Slim components to League's container.

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

## Settings

You can access Slim's internal configuration through the `settings` key on the container:

```php
$app = new \Jenssegers\Lean\App();

$app->getContainer()->get('settings')['displayErrorDetails'] = true;
```

Alternatively, an alias is registered that allows a bit more fluent way of working with settings:

```php
$app = new \Jenssegers\Lean\App();

$app->getContainer()->get(\Slim\Settings::class)->set('displayErrorDetails', true);
``` 

Read more about the available configuration options [here](https://www.slimframework.com/docs/v3/objects/application.html#slim-default-settings).

# Route arguments

By default, Lean will use method injection to pass arguments to your routes. This allows you to type-hint dependencies on method level (similar to the Laravel framework).

Route arguments will be passed as individual arguments to your method:

```php
$app->get('/books/{id}', function (Request $request, Response $response, string $id) {
    ...
});
```

They are also accessible through the `getAttribute` method.

```php
$app->get('/books/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    ....
});
```

If you want to disable this behaviour and use the default Slim way of route arguments, you can disable this feature be setting `methodInjection` to `false`:

```php
$app->getContainer()->get(\Slim\Settings::class)->set('methodInjection', false);
```

Read more about routes [here](http://www.slimframework.com/docs/v3/objects/router.html).

## Error Handlers

By default, Lean uses Slim's error handlers. There are different ways to implement an error handler for Slim, read more about them [here](https://www.slimframework.com/docs/v3/handlers/error.html).

Typically you would create a custom error handler class that looks like this:

```php
class CustomErrorHandler
{
    public function __invoke(ServerRequestInterface $request, Response $response, Throwable $exception)
    {
        return $response->withJson([
            'error' => 'Something went wrong',
        ], 500);
    }
}
```

Then you overwrite the default handler by adding it to the container:

```php
$app = new Jenssegers\Lean\App();

$app->getContainer()->share('errorHandler', function () {
    return new CustomErrorHandler();
});
```

Ideally, you would put this code inside a service provider. Read more about service providers above.

## Testing

``` bash
$ php ./vendor/bin/phpunit
```

## License

The MIT License (MIT).
