<?php
declare(strict_types = 1);

namespace OAF\Route;

use FastRoute\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;

class FastRouteBridge implements RouteBridgeInterface
{
    public function __construct(Dispatcher $router)
    {
        $this->router = $router;
    }

    public function dispatch(ServerRequestInterface $request) : Route
    {
        $route = $this->router->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        if ($route[0] === Dispatcher::NOT_FOUND) {
            // 404
            throw new \Error('boom');
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            throw new \Error('boom');
            //return $response->withStatus(405)->withHeader('Allow', implode(', ', $route[1]));
        }

        return new Route($route[1], $route[2]);
    }
}