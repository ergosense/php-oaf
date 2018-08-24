<?php
use Psr\Container\ContainerInterface;
use OAF\Encoders\ResponseEncoderInterface;
use OAF\Encoders\ResponseEncoder;
use OAF\Encoders\JsonEncoder;

return [
    /**
     * Configure response encoding. Together with the RequestHandler
     * middleware. This component will format an array into the appropriate
     * response requested by the user.
     */
    ResponseEncoderInterface::class => function (ContainerInterface $c) {
        return new ResponseEncoder([
          new JsonEncoder
        ]);
    }
];