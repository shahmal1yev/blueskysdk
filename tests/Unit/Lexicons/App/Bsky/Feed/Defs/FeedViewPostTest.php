<?php

namespace Tests\Unit\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Lexicons\App\Bsky\Feed\Defs\FeedViewPost;
use Atproto\Lexicons\App\Bsky\Feed\Defs\PostView;
use Atproto\Lexicons\App\Bsky\Feed\Defs\ReplyRef;
use Atproto\Lexicons\App\Bsky\Feed\Defs\ReasonPin;
use Atproto\Lexicons\App\Bsky\Feed\Defs\ReasonRepost;
use PHPUnit\Framework\TestCase;

class FeedViewPostTest extends TestCase
{
    public function test_nsid_is_correct(): void
    {
        $this->assertSame(
            'app.bsky.feed.defs#feedViewPost',
            FeedViewPost::nsid()
        );

        $this->assertSame(
            'app.bsky.feed.defs#feedViewPost',
            (new FeedViewPost([]))->nsid()
        );
    }

    public function test_casts_post_and_reply_ref_correctly(): void
    {
        $data = [
            'post' => ['uri' => 'at://foo/post/1'],
            'replyRef' => ['root' => ['uri' => 'at://foo/post/1']]
        ];

        $feed = new FeedViewPost($data);

        $this->assertInstanceOf(PostView::class, $feed->post());
        $this->assertInstanceOf(ReplyRef::class, $feed->replyRef());
    }

    public function test_casts_reason_as_reason_repost(): void
    {
        $data = [
            'post' => ['uri' => 'at://foo/post/1'],
            'reason' => [
                '$type' => 'app.bsky.feed.defs#reasonRepost',
                'by' => ['handle' => 'bob.bsky.social']
            ]
        ];

        $feed = new FeedViewPost($data);

        $this->assertInstanceOf(ReasonRepost::class, $feed->reason());
    }

    public function test_casts_reason_as_reason_pin(): void
    {
        $data = [
            'post' => ['uri' => 'at://foo/post/1'],
            'reason' => [
                '$type' => 'app.bsky.feed.defs#reasonPin',
                'by' => ['handle' => 'alice.bsky.social']
            ]
        ];

        $feed = new FeedViewPost($data);

        $this->assertInstanceOf(ReasonPin::class, $feed->reason());
    }

    public function test_casts_reason_invalid_type_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'post' => ['uri' => 'at://foo/post/1'],
            'reason' => [
                '$type' => 'invalid.defs#unknownReason'
            ]
        ];

        (new FeedViewPost($data))->reason();
    }
}
