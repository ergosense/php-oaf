<?php
namespace OAF\Traits;

use Psr\Http\Message\ServerRequestInterface;
use OAF\User\UserInterface;
use OAF\User\MemoryUser;
use OAF\User\GuestUser;

trait JwtUser
{
    public function authedUser(ServerRequestInterface $request) : UserInterface
    {
        $token = $request->getAttribute('token');

        if (!$token) {
            return new GuestUser;
        } else {
            return new MemoryUser($token['userId'], 'unknown');
        }
    }
}