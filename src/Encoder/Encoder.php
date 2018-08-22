<?php
namespace OAF\Encoder;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \Exception;

/**
 * Encode data into a desired response object. Used to ensure
 * content is presented to the user in the requested format where
 * applicable.
 */
class Encoder
{
  private $handlers = [];

  private function checkType($type, $check)
  {
    $match = preg_match(
      '/' . str_replace(['*', '/'], ['.*', '\/'], $check) . '/',
      $type
    );

    return $match;
  }

  public function register(EncoderInterface $handler)
  {
    $this->handlers[$handler->type()] = $handler;
    return $this;
  }

  public function getSupportedTypes()
  {
    return array_keys($this->handlers);
  }

  public function canEncode($check)
  {
    foreach (array_keys($this->handlers) as $type) {
      if ($this->checkType($type, $check)) return true;
    }

    return false;
  }

  public function encode($data, Request $request, Response $response)
  {
    // Get accepted content type
    $ct = $request->getHeaderLine('Accept');

    foreach ($this->handlers as $type => $handler) {
      // Run matching encoder, or run the first one in the list
      // if a user didn't specify a desired content type.
      if (!$ct || $this->checkType($type, $ct)) {
        $result = $handler->encode($data);

        $response->getBody()->write($result);

        // Fixate the response with the correct header
        return $response->withHeader('Content-Type', $type);
      }
    }

    return $response;
  }
}