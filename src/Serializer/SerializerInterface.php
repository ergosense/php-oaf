<?php
namespace OAF\Serializer;

use \Exception;

interface SerializerInterface
{
  public function type();

  public function serialize(array $data);
}