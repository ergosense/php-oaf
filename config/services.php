<?php
use Psr\Container\ContainerInterface;
use OAF\Encoder\ResponseEncoderInterface;
use OAF\Encoder\ResponseEncoder;
use OAF\Encoder\JsonEncoder;
use OAF\Invoker\Resolver;

$services = [
    /**
     * Configure response encoding. Together with the RequestHandler
     * middleware. This component will format an array into the appropriate
     * response requested by the user.
     */
    ResponseEncoderInterface::class => function (ContainerInterface $c) {
        return new ResponseEncoder([
          new JsonEncoder
        ]);
    },
    /**
     * Invoker
     */
    Invoker::class => function (ContainerInterface $c) {
        return Invoker($c->get(Resolver::class), $c->get(ResponseEncoderInterface::class));
    },
    /**
     * Resolver
     */
    Resolver::class => function (ContainerInterface $c) {
        return new Resolver($c);
    }
];

// Include route information
$services += require_once __DIR__ . '/services_routes.php';

// Include authorizers
$services += require_once __DIR__ . '/services_auth.php';

return $services;