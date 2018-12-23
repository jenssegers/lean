<?php

namespace Jenssegers\Lean\Tests\Stubs;

use League\Container\ServiceProvider\AbstractServiceProvider;

class ErrorServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'errorHandler',
    ];

    public function register()
    {
        $this->container->share('errorHandler', function () {
            return new ErrorHandler();
        });
    }
}
