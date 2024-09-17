<?php

namespace Tests\Feature;

use Atproto\Client;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Exceptions\BlueskyException;
use Atproto\Resources\Com\Atproto\Server\CreateSessionResource;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Tests\Supports\Reflection;

class ClientTest extends TestCase
{
    use Reflection;

    private Client $client;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();
    }

    /**
     * @throws BlueskyException
     * @throws ReflectionException
     */
    public function testGetProfile(): void
    {
        $username = $_ENV['BLUESKY_IDENTIFIER'];
        $password = $_ENV['BLUESKY_PASSWORD'];

        $this->assertIsString($username);
        $this->assertIsString($password);

        $this->client->authenticate(
            $username,
            $password
        );

        /** @var CreateSessionResource $authenticated */
        $authenticated = $this->getPropertyValue('authenticated', $this->client);

        $this->assertInstanceOf(ResourceContract::class, $authenticated);

        $this->assertIsString($authenticated->handle());;
        $this->assertSame($username, $authenticated->handle());
    }
}
