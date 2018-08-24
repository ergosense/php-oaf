<?php
declare(strict_types = 1);

namespace OAF\Middlewares;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

use OAF\Middlewares\RequestHandler\CallableResolver;
use OAF\Encoders\ResponseEncoderInterface;

class RequestHandler implements MiddlewareInterface
{
    /**
     * @var ContainerInterface Used to resolve the handlers
     */
    private $resolver;
    /**
     * Set the resolver instance.
     */
    public function __construct(CallableResolver $resolver, ResponseEncoderInterface $encoder)
    {
        $this->resolver = $resolver;
        $this->encoder = $encoder;
    }

    /**
     * Process a server request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $request->getAttribute('route');

        $callable = $this->resolver->resolve($route[1]);

        // TODO factory
        $response = new \Zend\Diactoros\Response();

        $arr = call_user_func($callable, $request, $response, $route[2]);

        // Allow circuit breaker responses, like redirects etc
        if ($arr instanceof ResponseInterface) {
            return $arr;
        }

        // Encode array data
        $response->getBody()->write($this->encoder->encode($request, $arr));
        return $response;
    }
}
