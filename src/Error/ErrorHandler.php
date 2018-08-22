<?php
namespace OAF\Error;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \Exception;
use OAF\Encoder\Encoder;

class ErrorHandler
{
  private $encoder;

  public function __construct(Encoder $encoder)
  {
    $this->encoder = $encoder;
  }

  protected function format(Exception $exception)
  {
    return [
      'error' => $exception->getMessage()
    ];
  }

  public function __invoke(Request $req, Response $res, Exception $e)
  {
    error_log(print_r($e->getTraceAsString(), 1));

    $res = $this->encoder->encode(
      $this->format($e),
      $req,
      $res
    );

    // TODO http mapping
    return $res->withStatus(500);

  }
}