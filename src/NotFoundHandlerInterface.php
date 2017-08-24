<?php

namespace Slim\ErrorHandling;

use Slim\Http\Response;

interface NotFoundHandlerInterface
{
    public function handleNotFound(Response $response) : Response;
    
    public function addNotFoundImplementation(string $notFoundImplementation): NotFoundHandlerInterface;
}
