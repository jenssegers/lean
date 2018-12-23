<?php

namespace Jenssegers\Lean\Tests\Stubs;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandler
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Exception $exception)
    {
        return $response->getBody()->write('Something went wrong');
    }
}
