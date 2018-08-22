<?php
namespace OAF\Auth;

use Psr\Http\Message\ServerRequestInterface as Request;
use \Firebase\JWT\JWT;

class JsonWebToken implements AuthInterface
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function authenticate(Request $request)
    {
        $auth = $request->getHeaderLine('Authorization');
        $match = preg_match('/bearer\s(.*)/i', $auth, $matches);
        $token = $match ? $matches[1] : null;

        if (!$token) return false;

        try {
            $decode = JWT::decode($token, $this->key, ['HS512']);
        } catch (\Exception $e) {
            error_log(print_r($e->getMessage(), 1));
            $decode = false;
        }

        return $decode->userId;
    }
}