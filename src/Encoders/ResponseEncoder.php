<?php
declare(strict_types = 1);

namespace OAF\Encoders;

use Psr\Http\Message\ServerRequestInterface;

class ResponseEncoder implements ResponseEncoderInterface
{
    private $encoders = [];

    public function __construct(array $encoders = [])
    {
        $this->encoders = $encoders;
    }

    public function add(EncoderInterface $encoder)
    {
        $this->encoders[] = $encoder;
    }

    public function getEncoders()
    {
        return $this->encoders;
    }

    public function encode(ServerRequestInterface $request, array $data)
    {
        $type = $request->getHeaderLine('Accept');

        foreach ($this->encoders as $encoder) {
            if (in_array($type, $encoder->supports())) {
                return $encoder->encode($data);
            }
        }

        throw new \Exception(sprintf('Unable to encode response: %s', $type));
    }
}