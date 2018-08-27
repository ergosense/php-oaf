<?php
declare(strict_types = 1);

namespace OAF;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Stratigility\MiddlewarePipe;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResolvablePipe
{
    public function __construct(ContainerInterface $container, MiddlewarePipeInterface $pipe = null)
    {
        $this->container = $container;
        $this->pipe = $pipe ? : new MiddlewarePipe;
    }

    public function pipe($middleware)
    {
        return $this->pipe->pipe(is_string($middleware) ? $this->container->get($middleware) : $middleware);
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        return $this->pipe->handle($request);
    }
}
