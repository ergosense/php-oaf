<?php
declare(strict_types = 1);

namespace OAF\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Respect\Validation\Exceptions\ExceptionInterface;
use InvalidArgumentException;

class ValidationExceptionHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (ExceptionInterface $e) {
            throw new InvalidArgumentException($e->getMainMessage());
        }
    }
}