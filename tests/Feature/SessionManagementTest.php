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

    private static array $credentials;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$credentials = [
            getenv('BLUESKY_IDENTIFIER'),
            getenv('BLUESKY_PASSWORD'),
        ];
    }

    private function session(?CreateSessionResponse $session = null): CreateSessionResponse
    {
        if ($session) {
            self::$credentials = array_merge(self::$credentials, [$session]);
        }

        return (new Client())->com()->atproto()->server()->createSession()
            ->forge(...self::$credentials)
            ->send();
    }

    public function testValidCredentialsReturnValidSession(): void
    {
        $this->assertInstanceOf(CreateSessionResponse::class, $this->session());
    }

    public function testInvalidCredentialsThrowException(): void
    {
        self::$credentials = ['invalid identifier', 'invalid password'];

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
        ]);

        $actualSession = $this->session($invalidSession);

        $this->assertNotSame($invalidSession, $actualSession);
    }

    public function testInvalidSessionAndCredentialsThrowException(): void
    {
        self::$credentials = [
            'invalid handle',
            'invalid password',
        ];

        $invalidSession = new CreateSessionResponse([
            'handle' => 'invalid identifier',
            'accessJwt' => 'invalid access token',
        ]);

        $this->expectException(BlueskyException::class);

        $this->session($invalidSession);
    }
}
