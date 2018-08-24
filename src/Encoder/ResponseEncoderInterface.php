<?php
declare(strict_types = 1);

namespace OAF\Encoder;

use Psr\Http\Message\ServerRequestInterface;

interface ResponseEncoderInterface
{
    public function encode(ServerRequestInterface $request, array $data);
}