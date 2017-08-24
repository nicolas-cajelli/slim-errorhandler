<?php

namespace Slim\ErrorHandling;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Slim\ErrorHandling\ResponseHandler\BadRequestJsonResponseHandler;
use Slim\ErrorHandling\ResponseHandler\NotAllowedJsonResponseHandler;
use Slim\ErrorHandling\ResponseHandler\NotFoundJsonResponseHandler;
use Slim\ErrorHandling\ResponseHandler\ServerErrorJsonResponseHandler;

function getConfiguration() : array
{
    $containerDefinition = [];
    $containerDefinition[Logger::class] = function (ContainerInterface $c) : Logger {
        $settings = $c->get('settings');
        $logger = new Logger(isset($settings['appName']) ? $settings['appName'] : 'my-app');
        $logger->pushProcessor(new UidProcessor());
        $logger->pushHandler($c->get(AbstractProcessingHandler::class));
        return $logger;
    };
    
    $containerDefinition[AbstractProcessingHandler::class] = function (ContainerInterface $c) : AbstractProcessingHandler {
        $settings = $c->get('settings');
        return new ErrorLogHandler(
            ErrorLogHandler::SAPI,
            isset($settings['logLevel']) ? $settings['logLevel'] : Logger::INFO
        );
    };
    
    $containerDefinition['errorHandler'] = function (ContainerInterface $c) {
        $errorHandler = new ErrorHandler(
            $c->get(Logger::class),
            $c->get('serverErrorResponseImpl'),
            $c->get('notFoundHandlerImpl'),
            $c->get('notAllowedHandlerImpl')
        );
        
        foreach ($c->get('responseHandlers') as $responseHandler) {
            $errorHandler->addResponseHandler($responseHandler);
        }
        return $errorHandler;
    };
    
    $containerDefinition['phpErrorHandler'] =  function(ContainerInterface $c) {
        return $c->get('errorHandler');
    };
    
    $containerDefinition['notFoundHandler'] = function (ContainerInterface $c) {
        return [$c->get('errorHandler'), 'handleNotFound'];
    };
    
    $containerDefinition['notAllowedHandler'] = function (ContainerInterface $c) {
        return [$c->get('errorHandler'), 'handleNotAllowed'];
    };

    $containerDefinition['notFoundHandlerImpl'] = function (ContainerInterface $c) {
        return $c->get(NotFoundJsonResponseHandler::class);
    };
    
    $containerDefinition['notAllowedHandlerImpl'] = function (ContainerInterface $c) {
        return $c->get(NotAllowedJsonResponseHandler::class);
    };
    
    $containerDefinition['serverErrorResponseImpl'] = function (ContainerInterface $c) {
        return $c->get(ServerErrorJsonResponseHandler::class);
    };
    
    $containerDefinition['responseHandlers'] = function (ContainerInterface $c) {
        return [
            $c->get(BadRequestJsonResponseHandler::class)
        ];
    };
    
    return $containerDefinition;
}