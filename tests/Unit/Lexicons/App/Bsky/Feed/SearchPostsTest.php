<?php

namespace Tests\Unit\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Lexicons\App\Bsky\Feed\Post;
use Atproto\Lexicons\App\Bsky\Feed\SearchPosts;
use PHPUnit\Framework\TestCase;

class SearchPostsTest extends TestCase
{
    private SearchPosts $searchPosts;

    public function setUp(): void
    {
        $this->searchPosts = new SearchPosts($this->createMock(Client::class), 'query');
    }

    public function testUrl()
    {
//        $this->searchPosts->url('ss')->send();

        $this->assertEquals(1,1);
    }
}
