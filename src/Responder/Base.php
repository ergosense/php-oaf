<?php
namespace OAF\Responder;

use OAF\Serializer\Serializer;

class Base
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    protected function format(array $data)
    {
        return [
            'data' => $data
        ];
    }

    public function respond(array $data, $request, $response)
    {
        return $this->serializer->serialize(
            $this->format($data),
            $request,
            $response
        );
    }
}