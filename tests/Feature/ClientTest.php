<?php

namespace Tests\Feature;

use Atproto\Client;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\BlueskyException;
use Atproto\Exceptions\Http\Response\AuthenticationRequiredException;
use Atproto\Exceptions\Http\Response\AuthMissingException;
use Atproto\Responses\App\Bsky\Actor\GetProfileResponse;
use Atproto\Responses\Com\Atproto\Server\CreateSessionResponse;
use Carbon\Carbon;
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
        $username = getenv('BLUESKY_IDENTIFIER');
        $password = getenv('BLUESKY_PASSWORD');

        $this->assertIsString($username);
        $this->assertIsString($password);

        $this->client->authenticate(
            $username,
            $password
        );

        /** @var CreateSessionResponse $authenticated */
        $authenticated = $this->getPropertyValue('authenticated', $this->client);

        $this->assertInstanceOf(ResponseContract::class, $authenticated);

        $this->assertIsString($authenticated->handle());
        $this->assertSame($username, $authenticated->handle());

        $profile = $this->client
            ->app()
            ->bsky()
            ->actor()
            ->getProfile()
            ->forge()
            ->actor($this->client->authenticated()->did())
            ->send();

        $this->assertInstanceOf(ResponseContract::class, $profile);
        $this->assertInstanceOf(GetProfileResponse::class, $profile);

        $this->assertInstanceOf(Carbon::class, $profile->createdAt());
    }

    /**
     * @throws BlueskyException
     */
    public function testClientThrowsExceptionWhenAuthenticationFails(): void
    {
        $this->expectException(AuthenticationRequiredException::class);
        $this->expectExceptionMessage("Invalid identifier or password");
        $this->expectExceptionCode(401);

        $this->client->authenticate(
            'invalid',
            'credentials'
        );
    }

    public function testClientThrowsExceptionWhenAuthenticationRequired(): void
    {
        $this->expectException(AuthMissingException::class);
        $this->expectExceptionMessage("Authentication Required");
        $this->expectExceptionCode(401);

        $this->client
            ->app()
            ->bsky()
            ->actor()
            ->getProfile()
            ->forge()
            ->send();
    }

    public function testObserverNotificationOnAuthentication(): void
    {
        $request = $this->client->app()
            ->bsky()
            ->actor()
            ->getProfile()
            ->forge();

        $this->client->authenticate(
            getenv('BLUESKY_IDENTIFIER'),
            getenv('BLUESKY_PASSWORD')
        );

        $response = $request->actor($this->client->authenticated()->did())
            ->build()
            ->send();

        $this->assertInstanceOf(ResponseContract::class, $response);
        $this->assertInstanceOf(GetProfileResponse::class, $response);
    }
}
