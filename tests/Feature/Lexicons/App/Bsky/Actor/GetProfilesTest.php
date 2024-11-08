<?php

namespace Tests\Feature\Lexicons\App\Bsky\Actor;

use Atproto\Client;
use Atproto\Collections\ActorCollection;
use Atproto\Responses\App\Bsky\Actor\GetProfilesResponse;
use Atproto\Responses\Objects\ProfileObject;
use Atproto\Responses\Objects\ProfilesObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GetProfilesTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        static::$client = new Client();

        static::$client->authenticate(
            getenv('BLUESKY_IDENTIFIER'),
            getenv('BLUESKY_PASSWORD'),
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testGetProfiles(): void
    {
        $request = static::$client->app()
            ->bsky()
            ->actor()
            ->getProfiles()
            ->forge()
            ->actors(new ActorCollection([
                static::$client->authenticated()->did()
            ]))
            ->build();

        $response = $request->send();

        $this->assertInstanceOf(GetProfilesResponse::class, $response);
        $this->assertInstanceOf(ProfilesObject::class, $response->profiles());
        $this->assertInstanceOf(ProfileObject::class, $response->profiles()->get(0));
    }
}
