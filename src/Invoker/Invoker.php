<?php
namespace OAF\Invoker;

use OAF\Encoder\ResponseEncoderInterface as Encoder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use OAF\Route\Route;

class Invoker
{
    public function __construct(Resolver $resolver, Encoder $encoder)
    {
        $this->resolver = $resolver;
        $this->encoder = $encoder;
    }

    public function invoke(Route $route, Request $reques, Response $res) : Response
    {
        $callable = $this->resolver->resolve($route->callable);

        $arr = call_user_func($callable, $reques, $res, $route->args);

        // Allow circuit breaker responses, like redirects etc
        if ($arr instanceof Response) {
            return $arr;
        }

        // Encode array data
        $res->getBody()->write($this->encoder->encode($reques, $arr));
        return $res;
    }
}