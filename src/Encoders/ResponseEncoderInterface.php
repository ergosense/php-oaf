<?php
declare(strict_types = 1);

namespace OAF\Encoders;

use Psr\Http\Message\ServerRequestInterface;

interface ResponseEncoderInterface
{
    public function encode(ServerRequestInterface $request, array $data);
}