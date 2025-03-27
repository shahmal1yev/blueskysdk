<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\Record;

use Atproto\Lexicons\App\Bsky\Embed\Record\ViewRecord;
use Atproto\Lexicons\App\Bsky\Actor\Defs\ProfileViewBasic;
use Atproto\Lexicons\Com\Atproto\Label\Defs\Label;
use Atproto\Lexicons\App\Bsky\Embed\Images\View as ImagesView;
use Atproto\Lexicons\App\Bsky\Embed\Record\View as RecordView;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ViewRecordTest extends TestCase
{
    public function test_nsid_is_correct(): void
    {
        $this->assertSame(
            'app.bsky.embed.record#viewRecord',
            ViewRecord::nsid()
        );

        $this->assertSame(
            'app.bsky.embed.record#viewRecord',
            (new ViewRecord([]))->nsid()
        );
    }

    public function test_scalar_and_object_fields_cast_correctly(): void
    {
        $now = Carbon::now()->toISOString();

        $data = [
            'uri' => 'at://foo/bar',
            'cid' => 'bafy123',
            'value' => 'Hello world',
            'replyCount' => 2,
            'repostCount' => 1,
            'likeCount' => 5,
            'quoteCount' => 0,
            'author' => ['did' => 'did:plc:abc', 'handle' => 'user.bsky.social'],
            'labels' => ['val' => 'test'],
            'indexedAt' => $now,
            'embeds' => []
        ];

        $view = new ViewRecord($data);

        $this->assertEquals('at://foo/bar', $view->uri());
        $this->assertEquals('bafy123', $view->cid());
        $this->assertEquals('Hello world', $view->value());
        $this->assertEquals(2, $view->replyCount());
        $this->assertInstanceOf(ProfileViewBasic::class, $view->author());
        $this->assertInstanceOf(Label::class, $view->labels());
        $this->assertInstanceOf(Carbon::class, $view->indexedAt());
    }

    public function test_embeds_casts_correctly_to_multiple_view_types(): void
    {
        $data = [
            'embeds' => [
                [
                    '$type' => 'app.bsky.embed.images#view',
                    'images' => [],
                ],
                [
                    '$type' => 'app.bsky.embed.record#view',
                    'record' => [
                        '$type' => 'app.bsky.embed.record#viewRecord',
                        'value' => 'test'
                    ]
                ]
            ]
        ];

        $view = new ViewRecord($data);
        $embeds = $view->embeds();

        $this->assertCount(2, $embeds);
        $this->assertInstanceOf(ImagesView::class, $embeds[0]);
        $this->assertInstanceOf(RecordView::class, $embeds[1]);
    }

    public function test_invalid_embed_type_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'embeds' => [
                [
                    '$type' => 'unknown#badView'
                ]
            ]
        ];

        (new ViewRecord($data))->embeds();
    }
}
