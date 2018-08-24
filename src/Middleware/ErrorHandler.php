<?php
namespace OAF\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandler implements MiddlewareInterface
{
    /**
     * Process a server request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Throwable $e) {
            error_log('I caught this!');
            error_log(print_r($e->getMessage(), true));
            error_log(print_r($e->getTraceAsString(), true));
            throw new \Error('boo');
        }
    }
}