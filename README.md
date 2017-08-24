Slim Error handling
===================

Error handling abstraction for slim framework. 
Forget about configurations, simplify your container definitions, and customize your response formats.

Install
-------

```bash
composer require nicolas-cajelli/slim-errorhandler
```

Configure
---------

Just load the configurations before start declaring your DI

```php
<?php
use function Slim\ErrorHandling\getConfiguration;

$config = getConfiguration();
$config[YourClass::class] = yourDefinitions();

```

Add your own response handlers:

```php
$config['responseHandlers'] = function(ContainerInterface $c) {
    return [$c->get(YourResponseHandler::class), $c->get(OtherResponseHandler::class)];
};
```

You can override: responseHandlers, serverErrorResponseImpl, notAllowedHandlerImpl & notFoundHandlerImpl