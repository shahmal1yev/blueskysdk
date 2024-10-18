<?php

namespace Tests\Feature\HTTP\API\Requests\App\Bsky\Actor;

use Atproto\Client;
use Atproto\Exceptions\BlueskyException;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\Http\Response\AuthMissingException;
use Atproto\Exceptions\Http\Response\InvalidTokenException;
use Atproto\Lexicons\App\Bsky\Actor\GetProfiles;
use Atproto\Resources\App\Bsky\Actor\GetProfilesResource;
use Atproto\Resources\Assets\ProfileAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Types\Primitive\StringType;
use PHPUnit\Framework\TestCase;

class GetProfilesTest extends TestCase
{
    private Client $client;
    private GetProfiles $request;

    /**
     * @throws BlueskyException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->request = $this->client
            ->app()
            ->bsky()
            ->actor()
            ->getProfiles()
            ->forge();
    }

    /**
     * @throws BlueskyException
     */
    private function authenticate(): void
    {
        $username = $_ENV['BLUESKY_IDENTIFIER'];
        $password = $_ENV['BLUESKY_PASSWORD'];

        $this->assertIsString($username);
        $this->assertIsString($password);

        $this->client->authenticate($username, $password);
    }

    /**
     * @throws InvalidArgumentException
     * @throws \Atproto\Exceptions\InvalidArgumentException
     * @throws BlueskyException
     */
    public function testGettingProfiles(): void
    {
        $this->authenticate();

        $profiles = new GenericCollection(new StringType(), [
            $this->client->authenticated()->did()
        ]);

        $response = $this->client
            ->app()
            ->bsky()
            ->actor()
            ->getProfiles()
            ->forge()
            ->actors($profiles)
            ->build()
            ->send();

        $this->assertInstanceOf(GetProfilesResource::class, $response);
        $this->assertSame($profiles->count(), $response->profiles()->count());

        $actualDidArr = array_map(fn (ProfileAsset $profile) => $profile->did(), $response->profiles()->toArray());

        $this->assertSame($profiles->toArray(), $actualDidArr);
    }

    public function testGettingProfilesThrowsExceptionWhenAuthTokenIsMissing(): void
    {
        $this->expectException(BlueskyException::class);
        $this->expectException(AuthMissingException::class);
        $this->expectExceptionMessage("Authentication Required");

        $this->request->send();
    }

    public function testGettingProfilesThrowsExceptionWhenAuthTokenIsInvalid(): void
    {
        $this->expectException(BlueskyException::class);
        $this->expectException(InvalidTokenException::class);
        $this->expectExceptionMessage("Malformed authorization header");
        $this->expectExceptionCode(400);

        $this->request->token('Bearer token')->send();
    }

    public function testGettingProfilesThrowsExceptionWhenActorsMissing(): void
    {
        $this->expectException(BlueskyException::class);
        $this->expectException(MissingFieldProvidedException::class);
        $this->expectExceptionMessage("Missing fields provided: actors");

        $this->authenticate();

        $request = (new GetProfiles($this->client))->build();

        $request->send();
    }
}
