<?php

namespace Slim\ErrorHandling;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;
use Throwable;

interface ResponseHandlerInterface
{
    /**
     */
    public function handle(Response $response, Throwable $exception);
}
