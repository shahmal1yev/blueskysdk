<?php

namespace Feature;

use Atproto\Client;
use Atproto\Exceptions\BlueskyException;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;
use PHPUnit\Framework\TestCase;
use Tests\Supports\Reflection;

class SessionManagementTest extends TestCase
{
    use Reflection;

    private array $credentials;

    public function setUp(): void
    {
        parent::setUp();

        $this->credentials = [
            getenv('BLUESKY_IDENTIFIER'),
            getenv('BLUESKY_PASSWORD'),
        ];
    }

    private function session(?CreateSessionResponse $session = null): CreateSessionResponse
    {
        if ($session) {
            $this->credentials = array_merge($this->credentials, [$session]);
        }

        return (new Client())->com()->atproto()->server()->createSession()
            ->forge(...$this->credentials)
            ->send();
    }

    public function testValidCredentialsReturnValidSession(): void
    {
        $this->assertInstanceOf(CreateSessionResponse::class, $this->session());
    }

    public function testInvalidCredentialsThrowException(): void
    {
        $this->credentials = ['invalid identifier', 'invalid password'];

        $this->expectException(BlueskyException::class);

        $this->session();
    }

    public function testValidSessionObjectIsUsedWhenProvidedWithValidCredentials(): void
    {
        $expectedSession = $this->session();
        $actualSession = $this->session($expectedSession);

        $this->assertSame($expectedSession, $actualSession);
    }

    public function testInvalidSessionObjectTriggersAuthenticationWithValidCredentials(): void
    {
        $invalidSession = new CreateSessionResponse([
            'handle' => 'invalid identifier',
            'accessJwt' => 'invalid access token',
            'refreshJwt' => 'invalid refresh token',
        ]);

        $actualSession = $this->session($invalidSession);

        $this->assertNotSame($invalidSession, $actualSession);
    }

    public function testInvalidSessionAndCredentialsThrowException(): void
    {
        $this->credentials = [
            'invalid handle',
            'invalid password',
        ];

        $invalidSession = new CreateSessionResponse([
            'handle' => 'invalid identifier',
            'accessJwt' => 'invalid access token',
            'refreshJwt' => 'invalid refresh token',
        ]);

        $this->expectException(BlueskyException::class);

        $this->session($invalidSession);
    }
}
