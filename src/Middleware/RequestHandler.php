<?php
declare(strict_types = 1);

namespace OAF\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

use OAF\Route\RouteBridgeInterface as RouteBridge;
use OAF\Encoder\ResponseEncoderInterface;
use OAF\Invoker\Invoker;

class RequestHandler implements MiddlewareInterface
{
    /**
     * Set the resolver instance.
     */
    public function __construct(RouteBridge $bridge, Invoker $invoker)
    {
        $this->invoker = $invoker;
        $this->bridge = $bridge;
    }

    /**
     * Process a server request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->bridge->dispatch($request);

        // TODO factory
        $response = new \Zend\Diactoros\Response();

        return $this->invoker->invoke($route, $request, $response);
    }
}
