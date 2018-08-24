<?php
declare(strict_types = 1);

namespace OAF\Route;

use Psr\Http\Message\ServerRequestInterface;

interface RouteBridgeInterface
{
    public function dispatch(ServerRequestInterface $request) : Route;
}