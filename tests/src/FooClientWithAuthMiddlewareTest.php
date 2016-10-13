<?php

namespace FooApi\RestClient\Tests;

use FooApi\RestClient\FooClient;
use FooApi\RestClient\FooClientWithAuthMiddleware;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Tests\Server;

/**
 * @coversDefaultClass \FooApi\RestClient\FooClientWithAuthMiddleware
 */
class FooClientWithAuthMiddlewareTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FooClient
     */
    public $client;

    public function setUp()
    {
        parent::setUp();

        // Start the guzzle test server.
        Server::start();
        register_shutdown_function(function(){Server::stop();});

        $this->client = FooClientWithAuthMiddleware::create([
            'base_uri' => Server::$url,
            'api_user' => $_SERVER['FOO_USER'],
            'api_pass' => $_SERVER['FOO_PASS'],
        ]);
    }

    /**
     * @covers ::create
     */
    public function testFactory() {
        self::assertInstanceOf(FooClientWithAuthMiddleware::class, $this->client);
    }

    public function testAuth() {

        Server::enqueue([new Response(200, [], json_encode(['status' => 'OK'], TRUE))]);

        $this->client->GetFoo();
        $request = Server::received()[0];

        self::assertNotEmpty($request->getHeader('Authorization'));
    }

}

