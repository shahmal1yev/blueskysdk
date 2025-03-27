<?php

namespace Tests\Unit\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Lexicons\App\Bsky\Feed\Defs\ReplyRef;
use Atproto\Lexicons\App\Bsky\Feed\Defs\PostView;
use Atproto\Lexicons\App\Bsky\Feed\Defs\NotFoundPost;
use Atproto\Lexicons\App\Bsky\Feed\Defs\BlockedPost;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileViewBasic;
use PHPUnit\Framework\TestCase;

class ReplyRefTest extends TestCase
{
    public function test_nsid_is_correct(): void
    {
        $this->assertSame(
            'app.bsky.feed.defs#replyRef',
            ReplyRef::nsid()
        );

        $this->assertSame(
            'app.bsky.feed.defs#replyRef',
            (new ReplyRef([]))->nsid()
        );
    }

    public function test_casts_root_as_post_view(): void
    {
        $data = [
            'root' => [
                '$type' => 'app.bsky.feed.defs#postView',
                'uri' => 'at://post'
            ]
        ];

        $ref = new ReplyRef($data);

        $this->assertInstanceOf(PostView::class, $ref->root());
    }

    public function test_casts_parent_as_blocked_post(): void
    {
        $data = [
            'parent' => [
                '$type' => 'app.bsky.feed.defs#blockedPost',
                'uri' => 'at://parent'
            ]
        ];

        $ref = new ReplyRef($data);

        $this->assertInstanceOf(BlockedPost::class, $ref->parent());
    }

    public function test_casts_grandparent_author_correctly(): void
    {
        $data = [
            'grandparentAuthor' => [
                'handle' => 'gma.bsky.social',
                'did' => 'did:plc:abc123'
            ]
        ];

        $ref = new ReplyRef($data);

        $this->assertInstanceOf(ProfileViewBasic::class, $ref->grandparentAuthor());
        $this->assertEquals('gma.bsky.social', $ref->grandparentAuthor()->handle());
    }

    public function test_invalid_union_type_throws(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'root' => [
                '$type' => 'unknown#badType'
            ]
        ];

        (new ReplyRef($data))->root();
    }
}
