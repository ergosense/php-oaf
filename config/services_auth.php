<?php
use Psr\Container\ContainerInterface;
use OAF\Auth\Context;
use OAF\Middleware\JwtAuth;

return [
    'jwt_key' => 'testkey',
    Context::class => function (ContainerInterface $c) {
        return new Context;
    },
    JwtAuth::class => function ($c) {
      return new JwtAuth($c->get(Context::class), $c->get('jwt_key'));
    }
];