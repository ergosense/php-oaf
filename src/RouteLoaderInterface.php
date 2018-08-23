<?php
namespace OAF;

use FastRoute\RouteCollector;

interface RouteLoaderInterface
{
  public function load(RouteCollector $r);
}