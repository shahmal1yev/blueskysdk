<?php

namespace Tests\Feature\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Support\Arr;
use PHPUnit\Framework\TestCase;

class SearchPostsTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        static::$client = new Client();

        static::$client->authenticate(
            getenv('BLUESKY_IDENTIFIER'),
            getenv('BLUESKY_PASSWORD')
        );
    }

    /**
     * @see https://bsky.app/profile/shahmal1yevv.bsky.social/post/3lccmota7wa23
     */
    public function testSearchPostsWith(): void
    {
        $postContent = 'Hello World! This post was sent from a feature test of the BlueSky PHP SDK. You can read the docs from here. Author? Yes, @here it is. #php #sdk #bluesky';
        $limit = 1;

        $foundPosts = static::$client->app()->bsky()->feed()->searchPosts()->forge($postContent)
            ->limit($limit)
            ->send();

        $this->assertSame($limit, $foundPosts->posts()->count());
        $this->assertSame($postContent, Arr::get($foundPosts->posts()->get(0)->record(), 'text'));
    }
}
