<?php

namespace Tests\Feature\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Responses\Objects\PostObject;
use PHPUnit\Framework\TestCase;

class GetTimelineTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        static::$client = new Client();

        static::$client->authenticate(getenv('BLUESKY_IDENTIFIER'), getenv('BLUESKY_PASSWORD'));
    }

    public function testGetTimeline(): void
    {
        $client = static::$client;

        $getTimeline = $client->app()->bsky()->feed()->getTimeline()->forge()
            ->limit(5);

        $response = $getTimeline->send();

        $this->assertNotEmpty($feed = $response->feed());

        foreach($feed as $entry) {
            $this->assertInstanceOf(PostObject::class, $post = $entry->post());
            $this->assertSame($client->authenticated()->did(), $post->author()->did());
        }
    }
}
