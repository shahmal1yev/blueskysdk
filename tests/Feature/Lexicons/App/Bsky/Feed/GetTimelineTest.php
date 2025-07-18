<?php

namespace Tests\Feature\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\External\View;
use Atproto\Lexicons\App\Bsky\Feed\Defs\PostView;
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
            $this->assertInstanceOf(PostView::class, $post = $entry->post());
//            $this->assertSame($client->authenticated()->handle(), $post->author()->handle());
        }
    }

    public function testGetPostEmbeds(): void
    {
        $client = static::$client;

        $getTimeline = bskyFacade($client)->getTimeline()->limit(50);
        $response = $getTimeline->send();

        /** @var array $feed */
        $feed = $response->feed();

        $this->assertIsIterable($feed);

        foreach($feed as $entry) {
            /** @var PostView $post */
            $post = $entry->post();

            if (! $post->has('embed')) {
                continue;
            }

            $embed = $post->resolve('embed');

            if ($embed->resolve('$type') === View::nsid()) {
                $embed = $embed->resolve('external');

                $this->assertTrue($embed->has('uri'));
                $this->assertTrue($embed->has('title'));
                $this->assertTrue($embed->has('description'));

                $this->assertIsString($embed->resolve('uri'));
                $this->assertIsString($embed->resolve('title'));
                $this->assertIsString($embed->resolve('description'));
            }
        }
    }
}
