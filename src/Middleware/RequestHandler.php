<?php
declare(strict_types = 1);

namespace OAF\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

use OAF\Route\RouteBridgeInterface as RouteBridge;
use OAF\ResolvablePipe;

class RequestHandler implements MiddlewareInterface
{
    /**
     * Set the resolver instance.
     */
    public function __construct(RouteBridge $bridge, ContainerInterface $container)
    {
        $this->container = $container;
        $this->bridge = $bridge;
    }

    /**
     * Process a server request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->bridge->dispatch($request);

        $callable = (array) $route->callable;

        $pipeline = new ResolvablePipe($this->container);

        foreach ($callable as $middleware) {
            $pipeline->pipe($middleware);
        }

        $pipeline->pipe($handler);

        return $pipeline->handle($request);
    }
}
