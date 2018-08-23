<?php
namespace OAF;

use OAF\RouteLoaderInterface;
use FastRoute\RouteCollector;

class PhpRouteLoader implements RouteLoaderInterface
{
  private $routeFile;

  public function __construct($routeFile)
  {
    $this->routeFile = $routeFile;
  }

  public function load(RouteCollector $r)
  {
    error_log('Loading PHP routes...');

    require_once $this->routeFile;
  }
}