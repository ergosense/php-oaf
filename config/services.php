<?php
use Psr\Container\ContainerInterface;
use OAF\Serializer\Serializer;
use OAF\Serializer\JsonDataSerializer;
use OAF\Middleware\ContentTypeMiddleware;
use OAF\Middleware\AuthMiddleware;
use OAF\Auth\JsonWebToken;

return [
    /**
     * Content serializer. Responsible for handling the "Accept"
     * header specified by the end user.
     */
    Serializer::class => function (ContainerInterface $c) {
        $serializer = new Serializer();

        // Register the allowed output serializers
        $serializer->register(new JsonDataSerializer());
        return $serializer;
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
    }
];