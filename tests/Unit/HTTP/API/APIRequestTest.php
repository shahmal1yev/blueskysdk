<?php

namespace Tests\Unit\HTTP\API;

use Atproto\HTTP\API\APIRequest;
use Atproto\HTTP\API\Requests\Com\Atproto\Server\CreateSession;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Tests\Supports\Reflection;

class APIRequestTest extends TestCase
{
    use Reflection;

    private APIRequest $request;
    private Generator $faker;
    private array $parameters = [];

    public function setUp(): void
    {
        $this->faker = Factory::create();

        $this->parameters = [
            'identifier' => $this->faker->userName,
            'password' => $this->faker->password,
        ];

        $this->request = new CreateSession(
            'Atproto\\HTTP\\API\\Requests\\',
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
