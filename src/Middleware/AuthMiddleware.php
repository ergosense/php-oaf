<?php
namespace OAF\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use OAF\Auth\AuthInterface;

class AuthMiddleware
{
    private $handlers = [];

    public function register(AuthInterface $handler)
    {
        $this->handlers[] = $handler;
        return $this;
    }

    /**
     * Example middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $id = null;

        foreach ($this->handlers as $handler) {
            $id = $handler->authenticate($request);
            $request = $request->withAttribute('user', $id);

            // Authentication successful
            if ($id) break;
        }

        if (!$id) throw new \Error('Not authenticated');

        return $next($request, $response);
    }
}