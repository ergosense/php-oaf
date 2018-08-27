<?php
use Psr\Container\ContainerInterface;
use OAF\Error\ErrorGenerator;
use OAF\Middleware\ErrorHandler;

$services = [
    ErrorHandler::class => function (ContainerInterface $c) {
        return new ErrorHandler($c->get(ErrorGenerator::class));
    },
    ErrorGenerator::class => function (ContainerInterface $c) {
        return new ErrorGenerator;
    }
];

// Include route information
$services += require_once __DIR__ . '/services_routes.php';

// Include authorizers
$services += require_once __DIR__ . '/services_auth.php';

return $services;