<?php

namespace Slim\ErrorHandling;

use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class ErrorHandler
{
    /**
     * @var Logger
     */
    protected $logger;
    
    /**
     * @var ResponseHandlerInterface[]
     */
    protected $responseHandlers = [];
    /**
     * @var ResponseHandlerInterface
     */
    protected $serverErrorResponseHandler;
    /**
     * @var NotFoundHandlerInterface
     */
    protected $notFoundHandler;
    /**
     * @var NotAllowedHandlerInterface
     */
    protected $notAllowedHandler;
    
    public function __construct(
        Logger $logger,
        ResponseHandlerInterface $serverErrorResponseHandler,
        NotFoundHandlerInterface $notFoundHandler,
        NotAllowedHandlerInterface $notAllowedHandler
    ) {
        $this->logger = $logger;
        $this->notFoundHandler = $notFoundHandler;
        $this->notAllowedHandler = $notAllowedHandler;
        $this->serverErrorResponseHandler = $serverErrorResponseHandler;
    }
    
    public function __invoke(
        ServerRequestInterface $request,
            Response $response,
            \Throwable $exception
    ) : Response
    {
        if (empty($this->responseHandlers)) {
            throw new \RuntimeException('No response handler defined.');
        }
        
        /**
         * @var ResponseHandlerInterface $responseHandler
         */
        foreach ($this->responseHandlers as $responseHandler) {
            $newResponse = $responseHandler->handle($response, $exception);
            if ($newResponse != null) {
                return $newResponse;
            }
        }
        
        /*
        if ($exception instanceof NotFoundException) {
            return $response->withJson(['errors' => [
                'message' => $exception->getMessage()
            ]], 404);
        }
        if ($exception instanceof UnauthorizedException) {
            return $response->withJson(['errors' => [
                'message' => $exception->getMessage()
            ]], 401);
        }
        */
        
        /*
        if (extension_loaded('newrelic')) {
            newrelic_notice_error($exception->getMessage(), $exception);
        }
        */
        $this->logger->error($exception->getMessage());
        return $this->serverErrorResponseHandler->handle($response, $exception);
        
        
    }
    
    public function handleNotFound(ServerRequestInterface $request, Response $response)
    {
        return $this->notFoundHandler->handleNotFound($response);
    }
    
    public function handleNotAllowed(ServerRequestInterface $request, Response $response)
    {
        return $this->notAllowedHandler->handleNotAllowed($response);
    }
    
    /**
     * @param ResponseHandlerInterface $responseHandler
     * @return ErrorHandler
     */
    public function addResponseHandler(ResponseHandlerInterface $responseHandler) : ErrorHandler
    {
        $this->responseHandlers[] = $responseHandler;
        return $this;
    }
    
    /**
     * @param ResponseHandlerInterface $responseHandler
     * @return ErrorHandler
     */
    public function setServerErrorResponseHandler(ResponseHandlerInterface $responseHandler) : ErrorHandler
    {
        $this->serverErrorResponseHandler = $responseHandler;
        return $this;
    }
    
    /**
     * @param NotFoundHandlerInterface $notFoundHandler
     * @return ErrorHandler
     */
    public function setNotFoundHandler(NotFoundHandlerInterface $notFoundHandler) : ErrorHandler
    {
        $this->notFoundHandler = $notFoundHandler;
        return $this;
    }
    
    /**
     * @param NotAllowedHandlerInterface $notAllowedHandler
     * @return ErrorHandler
     */
    public function setNotAllowedHandler(NotAllowedHandlerInterface $notAllowedHandler) : ErrorHandler
    {
        $this->notAllowedHandler = $notAllowedHandler;
        return $this;
    }
}
