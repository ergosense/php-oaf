<?php
declare(strict_types=1);

namespace OAF\Encoders;

class JsonEncoder implements EncoderInterface
{
    public function supportsExtension() : array
    {
        return ['json'];
    }

    public function supports() : array
    {
        return ['application/json'];
    }

    public function encode(array $data)
    {
        return json_encode($data);
    }
}