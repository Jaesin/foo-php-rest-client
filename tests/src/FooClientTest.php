<?php

namespace FooApi\RestClient\Tests;

use FooApi\RestClient\FooClient;
use GuzzleHttp\Command\ResultInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Tests\Server;

/**
 * @coversDefaultClass \FooApi\RestClient\FooClient
 */
class FooClientTest extends \PHPUnit_Framework_TestCase
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

        $this->client = FooClient::create([
            'base_uri' => Server::$url,
            'api_user' => $_SERVER['FOO_USER'],
            'api_pass' => $_SERVER['FOO_PASS'],
        ]);
    }

    /**
     * @covers ::create
     */
    public function testFactory() {
        self::assertInstanceOf(FooClient::class, $this->client);
    }

    public function testAuth() {

        Server::enqueue([new Response(200, [], json_encode(['status' => 'OK'], TRUE))]);

        $this->client->GetFoo();
        $request = Server::received()[0];

        self::assertNotEmpty($request->getHeader('Authorization'));
    }

    public function testGetFoo() {
        $foo = [
            'id' => '1',
            'name' => 'Foo',
            'description' => 'The best ever Foo.',
        ];
        Server::enqueue([new Response(200, [], json_encode(['status' => 'OK','Foo' => $foo], TRUE))]);
        $response = $this->client->GetFoo();

        self::assertInstanceOf(ResultInterface::class, $response);
        self::assertEquals($foo, $response->toArray()['Foo']);
    }

    public function testCreateFoo() {
        $foo = [
            'id' => 1,
            'name' => 'Foo',
            'description' => 'The best ever Foo.',
        ];
        Server::enqueue([new Response(200, [], json_encode(['status' => 'OK','Foo' => $foo], TRUE))]);
        $response = $this->client->CreateFoo($foo);

        self::assertInstanceOf(ResultInterface::class, $response);
        self::assertEquals($foo, $response->toArray()['Foo']);
    }

}

