<?php
namespace OAF;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Diactoros\Response;

use FastRoute\Dispatcher;

use \Middlewares\FastRoute;
use \middlewares\JsonPayload;

use DI\ContainerBuilder;

class Kernel implements RequestHandlerInterface
{
    private $stack;
    private $c;

    public function __construct(array $definitionFiles = [])
    {
        // Build the default framework container
        $builder = new ContainerBuilder;

        // Default framework services
        $builder->addDefinitions(__DIR__ . '/../config/services.php');

        // Custom user services
        foreach ($definitionFiles as $def) {
            $builder->addDefinitions($def);
        }

        // Construct the container
        $this->c = $builder->build();

        // LIFO middleware stack
        $this->stack = new \SplStack;

        // Default framework middleware
        $this
            // Routing
            ->with(new \Middlewares\RequestHandler($this->c))
            ->with(new FastRoute($this->c->get(Dispatcher::class)))
            ->with(new JsonPayload);
    }

    public function with(MiddlewareInterface $middleware)
    {
        $this->stack->push($middleware);
        return $this;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $defaultResponse = new Response("php://memory", 501);

        if (!$this->stack->isEmpty()) {
            $middleware = $this->stack->pop();
            $defaultResponse = $middleware->process($request, $this);
        }

        return $defaultResponse;
    }

    /**
     * Helper method, which returns true if the provided response must not output a body and false
     * if the response could have a body.
     *
     * @see https://tools.ietf.org/html/rfc7231
     *
     * @param ResponseInterface $response
     * @return bool
     */
    protected function isEmptyResponse(ResponseInterface $response)
    {
        if (method_exists($response, 'isEmpty')) {
            return $response->isEmpty();
        }

        return in_array($response->getStatusCode(), [204, 205, 304]);
    }

    public function emit($response)
    {
        // Send response
        if (!headers_sent()) {
            // Headers
            foreach ($response->getHeaders() as $name => $values) {
                foreach ($values as $value) {
                    header(sprintf('%s: %s', $name, $value), false);
                }
            }

            // Set the status _after_ the headers, because of PHP's "helpful" behavior with location headers.
            // See https://github.com/slimphp/Slim/issues/1730

            // Status
            header(sprintf(
                'HTTP/%s %s %s',
                $response->getProtocolVersion(),
                $response->getStatusCode(),
                $response->getReasonPhrase()
            ));
        }

        // Body
        if (!$this->isEmptyResponse($response)) {
            $body = $response->getBody();
            if ($body->isSeekable()) {
                $body->rewind();
            }

            $chunkSize      = 4096;

            $contentLength  = $response->getHeaderLine('Content-Length');
            if (!$contentLength) {
                $contentLength = $body->getSize();
            }


            if (isset($contentLength)) {
                $amountToRead = $contentLength;
                while ($amountToRead > 0 && !$body->eof()) {
                    $data = $body->read(min($chunkSize, $amountToRead));
                    echo $data;

                    $amountToRead -= strlen($data);

                    if (connection_status() != CONNECTION_NORMAL) {
                        break;
                    }
                }
            } else {
                while (!$body->eof()) {
                    echo $body->read($chunkSize);
                    if (connection_status() != CONNECTION_NORMAL) {
                        break;
                    }
                }
            }
        }
    }
}