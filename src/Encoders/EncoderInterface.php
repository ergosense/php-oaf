<?php
declare(strict_types=1);

namespace OAF\Encoders;

interface EncoderInterface
{
    public function supportsExtension() : array;

    public function supports() : array;

    public function encode(array $data);
}