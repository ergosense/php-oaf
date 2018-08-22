<?php
use Psr\Container\ContainerInterface;
use OAF\Encoder\Encoder;
use OAF\Encoder\JsonDataEncoder;
use OAF\Middleware\ContentTypeMiddleware;
use OAF\Middleware\AuthMiddleware;
use OAF\Auth\JsonWebToken;
use OAF\Error\ErrorHandler;
use Symfony\Console\Application\Application;

return [
    /**
     * Content serializer. Responsible for handling the "Accept"
     * header specified by the end user.
     */
    Encoder::class => function (ContainerInterface $c) {
        $encoder = new Encoder();

        // Register the allowed output serializers
        $encoder->register(new JsonDataEncoder());
        return $encoder;
    },
    /**
     * Authentication. Ensures user has access to our system
     * and populates the route with the ID of the user requesting the
     * resource.
     */
    AuthMiddleware::class => function (ContainerInterface $c) {
        $auth = new AuthMiddleware();

        // Register the allowed authentication methods
        $auth->register(new JsonWebToken($c->get('settings.jwt_key')));
        return $auth;
    },
    /**
     * Overwrite the default Slim error handler
     */
    'errorHandler' => function (ContainerInterface $c) {
        return new ErrorHandler($c->get(Encoder::class));
    },
    Application::class => function (ContainerInterface $c) {
        return new Application('OAF', '1.0');
    }
];