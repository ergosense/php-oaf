<?php
use Psr\Container\ContainerInterface;
use OAF\Route\RouteBridgeInterface;
use OAF\Route\FastRouteBridge;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;
use FastRoute\DataGenerator;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\GroupCountBased as GroupCountBasedGenerator;

return [
    /**
     * Default route bridge
     */
    RouteBridgeInterface::class => function (ContainerInterface $c) {
        return new FastRouteBridge($c->get(Dispatcher::class));
    },
    /**
     * Fast Route configuration
     */
    RouteParser::class => function ($c) { return new Std; },
    DataGenerator::class => function ($c) { return new GroupCountBasedGenerator; },
    RouteCollector::class => function ($c) {
        return new RouteCollector(
          $c->get(RouteParser::class),
          $c->get(DataGenerator::class)
        );

        return $collector;
    },
    Dispatcher::class => function ($c) {
        $collector = $c->get(RouteCollector::class);
        return new GroupCountBased($collector->getData());
    },
];