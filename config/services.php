<?php
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use FastRoute\RouteParser;
use FastRoute\DataGenerator;

use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteParser\Std;
use FastRoute\DataGenerator\GroupCountBased as GroupCountBasedGenerator;
use OAF\RouteLoaderInterface;
use OAF\PhpRouteLoader;

return [
  RouteParser::class => function ($c) {
    return new Std;
  },
  DataGenerator::class => function ($c) {
    return new GroupCountBasedGenerator;
  },
  RouteLoaderInterface::class => function ($c) {
    return new PhpRouteLoader($c->get('routes'));
  },
  RouteCollector::class => function ($c) {
    $collector = new RouteCollector(
      $c->get(RouteParser::class),
      $c->get(DataGenerator::class)
    );

    $loader = $c->get(RouteLoaderInterface::class);
    $loader->load($collector);

    return $collector;
  },
  Dispatcher::class => function ($c) {
    $collector = $c->get(RouteCollector::class);
    return new GroupCountBased($collector->getData());
  }
];