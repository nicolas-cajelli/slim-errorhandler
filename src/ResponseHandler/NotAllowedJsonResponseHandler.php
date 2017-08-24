<?php

namespace Slim\ErrorHandling\ResponseHandler;

use Slim\ErrorHandling\NotAllowedHandlerInterface;
use Slim\Http\Response;

class NotAllowedJsonResponseHandler implements NotAllowedHandlerInterface
{
    
    public function handleNotAllowed(Response $response): Response
    {
        return $response
            ->withStatus(405)
            ->withHeader('Content-Type', 'application/json');
    }
}
