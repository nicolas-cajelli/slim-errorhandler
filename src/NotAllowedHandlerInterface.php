<?php

namespace Slim\ErrorHandling;

use Slim\Http\Response;

interface NotAllowedHandlerInterface
{
    public function handleNotAllowed(Response $response) : Response;
}
