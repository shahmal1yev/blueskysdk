<?php

namespace Tests\Unit\Lexicons\App\Bsky\Feed\Defs;

use Atproto\Lexicons\App\Bsky\Feed\Defs\PostView;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileViewBasic;
use Atproto\Lexicons\App\Bsky\Embed\Images\View as ImagesView;
use Atproto\Lexicons\App\Bsky\Embed\Video\View as VideoView;
use Atproto\Lexicons\App\Bsky\Embed\External\View as ExternalView;
use Atproto\Lexicons\App\Bsky\Embed\Record\View as RecordView;
use Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia\View as RecordWithMediaView;
use Atproto\Lexicons\App\Bsky\Feed\Defs\ViewerState;
use Atproto\Lexicons\App\Bsky\Feed\Defs\ThreadgateView;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class PostViewTest extends TestCase
{
    public function test_nsid_is_correct(): void
    {
        $this->assertSame(
            'app.bsky.feed.defs#postView',
            PostView::nsid()
        );

        $this->assertSame(
            'app.bsky.feed.defs#postView',
            (new PostView([]))->nsid()
        );
    }

    public function test_casts_basic_fields_correctly(): void
    {
        $now = Carbon::now()->toISOString();

        $data = [
            'uri' => 'at://foo/post/1',
            'cid' => 'bafy123',
            'record' => 'some-record-ref',
            'replyCount' => 3,
            'repostCount' => 1,
            'likeCount' => 7,
            'quoteCount' => 2,
            'indexedAt' => $now,
            'author' => ['handle' => 'john.bsky.social'],
            'viewer' => ['muted' => false],
            'labels' => ['val' => 'tag'],
            'threadgate' => ['uri' => 'at://foo/thread']
        ];

        $post = new PostView($data);

        $this->assertEquals('at://foo/post/1', $post->uri());
        $this->assertEquals('bafy123', $post->cid());
        $this->assertEquals('some-record-ref', $post->record());
        $this->assertEquals(3, $post->replyCount());
        $this->assertEquals(1, $post->repostCount());
        $this->assertEquals(7, $post->likeCount());
        $this->assertEquals(2, $post->quoteCount());

        $this->assertInstanceOf(ProfileViewBasic::class, $post->author());
        $this->assertInstanceOf(ViewerState::class, $post->viewer());
        $this->assertInstanceOf(Label::class, $post->labels());
        $this->assertInstanceOf(ThreadgateView::class, $post->threadgate());
        $this->assertInstanceOf(Carbon::class, $post->indexedAt());
    }

    public function test_embed_casts_to_images_view(): void
    {
        $data = [
            'embed' => [
                '$type' => 'app.bsky.embed.images#view',
                'images' => [],
            ]
        ];

        $post = new PostView($data);

        $this->assertInstanceOf(ImagesView::class, $post->embed());
    }

    public function test_embed_casts_to_record_with_media(): void
    {
        $data = [
            'embed' => [
                '$type' => 'app.bsky.embed.recordWithMedia#view',
                'record' => ['uri' => 'at://foo'],
                'media' => [
                    '$type' => 'app.bsky.embed.external#view',
                    'uri' => 'at://bar'
                ]
            ]
        ];

        $post = new PostView($data);

        $this->assertInstanceOf(RecordWithMediaView::class, $post->embed());
    }

    public function test_invalid_embed_type_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'embed' => [
                '$type' => 'app.unknown#invalid',
            ]
        ];

        (new PostView($data))->embed();
    }
}
