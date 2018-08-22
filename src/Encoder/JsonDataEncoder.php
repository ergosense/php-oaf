<?php
namespace OAF\Encoder;

use Slim\Http\Body;
use Psr\Http\Message\ResponseInterface;
use \Exception;

class JsonDataEncoder implements EncoderInterface
{
  public function type()
  {
    return 'application/json';
  }

  public function encode(array $data)
  {
    return json_encode($data);
  }
}