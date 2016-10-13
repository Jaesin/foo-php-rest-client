<?php

namespace FooApi\RestClient;

use GuzzleHttp\Client;
use GuzzleHttp\Command\Guzzle\Description;
use GuzzleHttp\Command\Guzzle\GuzzleClient;

class FooClient extends GuzzleClient {

    public static function create($config = []) {
        // Load the service description file.
        $service_description = new Description(
            ['baseUrl' => $config['base_uri']] + (array) json_decode(file_get_contents(__DIR__ . '/../service.json'), TRUE)
        );

        // Creates the client and sets the default request headers.
        $client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'auth' =>  [$config['api_user'], $config['api_pass']],
        ]);

        return new static($client, $service_description, NULL, NULL, NULL, $config);
    }
}
