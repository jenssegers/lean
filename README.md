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

$app = new Jenssegers\Lean\App();

$app->get('/hello/{name}', function (Request $request, Response $response, $name) {
    return $response->write('Hello, ' . $name);
});

$app->run();
```

Behind the scenes, the container is prepared using a `SlimServiceProvider`. If you prefer to prepare it yourself you can do the following:


``` php
require 'vendor/autoload.php';

$container = new League\Container\Container;
$container->delegate(new League\Container\ReflectionContainer);
$container->addServiceProvider(new Jenssegers\Lean\SlimServiceProvider);

$app = new Slim\App($container);

$app->get('/hello/{name}', function (Request $request, Response $response, $name) {
    return $response->write('Hello, ' . $name);
});

$app->run();
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT).
