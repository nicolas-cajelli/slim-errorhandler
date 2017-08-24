<?php

namespace Slim\ErrorHandling\ResponseHandler;

use Slim\ErrorHandling\ResponseHandlerInterface;
use Slim\Http\Response;
use Throwable;

class BadRequestJsonResponseHandler implements ResponseHandlerInterface
{
    
    /**
     *
     */
    public function handle(Response $response, Throwable $exception)
    {
        if (
            ($exception instanceof \DomainException)
            || ($exception instanceof \InvalidArgumentException)
        ) {
            return $response->withJson(['errors' => [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]], 400);
        }
        
        return null;
    }
}
