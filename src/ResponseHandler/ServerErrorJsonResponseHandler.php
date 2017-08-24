<?php

namespace Slim\ErrorHandling\ResponseHandler;

use Slim\ErrorHandling\ResponseHandlerInterface;
use Slim\Http\Response;
use Throwable;

class ServerErrorJsonResponseHandler implements ResponseHandlerInterface
{
    /**
     */
    public function handle(Response $response, Throwable $exception)
    {
        return $response->withJson(['errors' => [
            'message' => $exception->getMessage()
        ]], 500);
    }
}
