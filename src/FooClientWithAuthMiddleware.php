<?php

namespace FooApi\RestClient;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;

class FooClientWithAuthMiddleware extends GuzzleClient {

    public static function create($config = []) {
        $handler_stack = HandlerStack::create();
        // Add an authentication handler to the stack.
        $handler_stack->push(function (callable $handler) use ($config) {
            return function (RequestInterface $request, array $options) use ($handler, $config) {
                // Return the request with a Bearer authorization header.
                return $handler(
                    $request->withAddedHeader('Authorization', 'Bearer 080042cad6356ad5dc0a720c18b53b8e53d4c274'),
                    $options
                );
            };
        }, 'auth');
        // Load the service description file.
        $service_description = new Description(
            ['baseUrl' => $config['base_uri']] + (array) json_decode(file_get_contents(__DIR__ . '/../service.json'), TRUE)
        );

        return new static(new Client(['handler'=>$handler_stack]), $service_description, NULL, NULL, NULL, $config);
    }

}

