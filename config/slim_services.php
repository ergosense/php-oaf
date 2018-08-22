<?php
/**
 * DO NOT EDIT: Instead override the values in a different
 * service file.
 *
 * Please look at DefaultServicesProvider.php in the Slim
 * repository. We are essentially copying that class here.
 * We would use that class, but the PSR-11 doesn't define array-like
 * access which the pimple DI uses. PHP-DI uses array PHP definitions
 * like used here.
 */
use Psr\Container\ContainerInterface;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Http\Environment;
use Slim\Handlers\PhpError;
use Slim\Handlers\Error;
use Slim\Handlers\NotFound;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\CallableResolver;

use function DI\get;

return [
    'settings.httpVersion' => '1.1',
    'settings.responseChunkSize' => 4096,
    'settings.outputBuffering' => 'append',
    'settings.determineRouteBeforeAppMiddleware' => false,
    'settings.displayErrorDetails' => true,
    'settings.addContentLengthHeader' => true,
    'settings.routerCacheFile' => false,
    // Slim framework specific definitions. We use flat values when
    // modifying cause it's easier to read and also works better with PHP-DI
    'settings' => [
        'httpVersion' => get('settings.httpVersion'),
        'responseChunkSize' => get('settings.responseChunkSize'),
        'outputBuffering' => get('settings.outputBuffering'),
        'determineRouteBeforeAppMiddleware' => get('settings.determineRouteBeforeAppMiddleware'),
        'displayErrorDetails' => get('settings.displayErrorDetails'),
        'addContentLengthHeader' => get('settings.addContentLengthHeader'),
        'routerCacheFile' => get('settings.routerCacheFile'),
    ],
    'environment' => function () {
        return new Environment($_SERVER);
    },
    'request' => function (ContainerInterface $c) {
        return Request::createFromEnvironment($c->get('environment'));
    },
    'response' => function (ContainerInterface $c) {
        $headers = new Headers(['Content-Type' => 'text/html; charset=UTF-8']);
        $response = new Response(200, $headers);
        return $response->withProtocolVersion($c->get('settings')['httpVersion']);
    },
    'router' => function (ContainerInterface $c) {
        $router = (new Router)->setCacheFile($c->get('settings')['routerCacheFile']);
        if (method_exists($router, 'setContainer')) {
            $router->setContainer($c);
        }

        return $router;
    },
    'foundHandler' => function () {
        return new RequestResponse;
    },
    'phpErrorHandler' => function (ContainerInterface $c) {
        return new PhpError($c->get('settings')['displayErrorDetails']);
    },
    'errorHandler' => function (ContainerInterface $c) {
        return new Error($c->get('settings')['displayErrorDetails']);
    },
    'notFoundHandler' => function () {
        return new NotFound;
    },
    'notAllowedHandler' => function () {
        return new NotAllowed;
    },
    'callableResolver' => function (ContainerInterface $c) {
        return new CallableResolver($c);
    }
];