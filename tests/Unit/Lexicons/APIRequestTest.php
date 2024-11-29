<?php

namespace Tests\Unit\Lexicons;

use Atproto\Lexicons\Com\Atproto\Server\CreateSession;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class APIRequestTest extends TestCase
{
    private CreateSession $request;
    private array $parameters;

    public function setUp(): void
    {
        $faker = Factory::create();

        $this->parameters = [
            'identifier' => $faker->userName,
            'password' => $faker->password,
        ];

        $this->request = new CreateSession(
            $this->parameters['identifier'],
            $this->parameters['password']
        );
    }

    public function testPath(): void
    {
        $expected = "/xrpc/com.atproto.server.createSession";
        $actual = $this->request->path();

        $this->assertSame($expected, $actual);
    }

    public function testParameters(): void
    {
        $expected = $this->parameters;
        $actual = $this->request->parameters();

        $this->assertSame($expected, $actual);
    }
}
