<?php

namespace Tests\Unit\HTTP\API;

use Atproto\Client;
use Atproto\HTTP\API\APIRequest;
use Atproto\HTTP\API\Requests\Com\Atproto\Server\CreateSession;
use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Tests\Supports\Reflection;

class APIRequestTest extends TestCase
{
    use Reflection;

    private APIRequest $request;
    private array $parameters = [];

    public function setUp(): void
    {
        $faker = Factory::create();

        $clientMock = $this->createMock(Client::class);

        $clientMock->method('path')->willReturn(str_replace(
            'Atproto\\HTTP\\API\\Requests\\',
            '',
            CreateSession::class
        ));

        $this->parameters = [
            'identifier' => $faker->userName,
            'password' => $faker->password,
        ];

        $this->request = new CreateSession(
            $clientMock,
            $this->parameters['identifier'],
            $this->parameters['password']
        );
    }

    public function testPath(): void
    {
        $expected = "xrpc/com.atproto.server.createSession";
        $actual = $this->request->path();

        $this->assertSame($expected, $actual);
    }

    public function testParameters(): void
    {
        $expected = $this->parameters;
        $actual = $this->request->parameters();

        $this->assertSame($expected, $actual);
    }

    public function testOrigin(): void
    {
        $expected = 'https://bsky.social';
        $actual = $this->request->origin();

        $this->assertSame($expected, $actual);
    }
}
