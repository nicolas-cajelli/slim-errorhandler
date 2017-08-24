<?php

namespace Slim\ErrorHandling\ResponseHandler;

use Slim\ErrorHandling\NotFoundHandlerInterface;
use Slim\ErrorHandling\ResponseHandlerInterface;
use Slim\Http\Response;
use Throwable;

class NotFoundJsonResponseHandler implements ResponseHandlerInterface, NotFoundHandlerInterface
{
    protected $notFoundImplementations = [];
    
    public function handleNotFound(Response $response): Response
    {
        return $response
            ->withStatus(404)
            ->withHeader('Content-Type', 'application/json');
    }
    
    /**
     */
    public function handle(Response $response, Throwable $exception)
    {
        foreach ($this->notFoundImplementations as $implementation) {
            if ($exception instanceof $implementation) {
                return $this->handleNotFound($response);
            }
        }
        return null;
    }
    
    /**
     * @param string $notFoundImplementation
     * @return NotFoundHandlerInterface
     */
    public function addNotFoundImplementation(string $notFoundImplementation): NotFoundHandlerInterface
    {
        $this->notFoundImplementations[] = $notFoundImplementation;
        return $this;
    }
}
