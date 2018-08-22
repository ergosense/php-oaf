<?php
namespace OAF\Encoder;

use \Exception;

interface EncoderInterface
{
  public function type();

  public function encode(array $data);
}