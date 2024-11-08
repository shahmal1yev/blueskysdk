<?php

namespace Tests\Feature\Lexicons\App\Bsky\Graph;

use Atproto\Client;
use Atproto\Responses\Objects\FollowersObject;
use PHPUnit\Framework\TestCase;

class GetFollowersTest extends TestCase
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

    public function testGetFollowers()
    {
        $client = static::$client;

        $request = $client->app()
            ->bsky()
            ->graph()
            ->getFollowers()
            ->forge();

        $request->actor($client->authenticated()->did())
            ->build();

        $response = $request->send();

        $this->assertSame($client->authenticated()->did(), $response->subject()->did());
        $this->assertInstanceOf(FollowersObject::class, $response->followers());
    }
}
