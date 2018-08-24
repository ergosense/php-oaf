<?php
declare(strict_types = 1);

namespace OAF\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Firebase\JWT\JWT;
use OAF\Auth\MemoryUser;
use OAF\Auth\Context;

class JwtAuth implements MiddlewareInterface
{
    public function __construct(Context $context, $secret)
    {
        $this->secret = $secret;
        $this->context = $context;
    }

    public function load($id)
    {
        return new MemoryUser($id, 'test@user.com');
    }

    /**
     * Process a server request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine('Authorization');
        $jwt = preg_replace('/bearer\s*/i', '', $header);
        $decoded = JWT::decode($jwt, $this->secret, array('HS512'));

        $user = $this->load($decoded->userId);

        $this->context->setUser($user);

        return $handler->handle($request);
    }
}