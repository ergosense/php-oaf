<?php
namespace OAF\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use OAF\Serializer\Serializer;

class ContentTypeMiddleware
{
    // These values are included as part of the slim framework
    static private $defaultSupportedTypes = [
        'application/json',
        'application/xml',
        'text/xml',
        'application/x-www-form-urlencoded'
    ];

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
        // Slim includes a set of default body parsers, for now we have
        // no intention of expanding on this, but it would need to expand in future
        // if more types are added.
        $ct = $request->getHeaderLine('Content-Type');

        // We don't need to parse the body here, the slim framework
        // does this automatically for us. The results are available with
        // the getParsedBody() method.

        if (!in_array($ct, static::$defaultSupportedTypes)) {
            return $response->withHeader(
                'Accept',
                implode(", ", static::$defaultSupportedTypes)
            );
        }
        //throw new \Exception('poop');

        return $next($request, $response);
    }
}