<?php
namespace OAF\Responder;

use OAF\Encoder\Encoder;

class Base
{
    private $encoder;

    public function __construct(Encoder $encoder)
    {
        $this->encoder = $encoder;
    }

    protected function format(array $data)
    {
        return [
            'data' => $data
        ];
    }

    public function respond(array $data, $request, $response)
    {
        return $this->encoder->encode(
            $this->format($data),
            $request,
            $response
        );
    }
}