<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\RecordWithMedia;

use Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia\View;
use Atproto\Lexicons\App\Bsky\Embed\External\View as ExternalView;
use Atproto\Lexicons\App\Bsky\Embed\Images\View as ImagesView;
use Atproto\Lexicons\App\Bsky\Embed\Video\View as VideoView;
use PHPUnit\Framework\TestCase;

class ViewTest extends TestCase
{
    public function test_nsid_is_correct(): void
    {
        $this->assertSame(
            'app.bsky.embed.recordWithMedia#view',
            View::nsid()
        );

        $this->assertSame(
            'app.bsky.embed.recordWithMedia#view',
            (new View([]))->nsid()
        );
    }

    public function test_record_casts_to_external_view(): void
    {
        $data = [
            'record' => ['uri' => 'at://external/uri'],
            'media' => [
                '$type' => 'app.bsky.embed.external#view',
                'uri' => 'at://media'
            ]
        ];

        $view = new View($data);

        $this->assertInstanceOf(ExternalView::class, $view->record());
    }

    public function test_media_casts_to_images_view(): void
    {
        $data = [
            'record' => ['uri' => 'at://external/uri'],
            'media' => [
                '$type' => 'app.bsky.embed.images#view',
                'images' => []
            ]
        ];

        $view = new View($data);

        $this->assertInstanceOf(ImagesView::class, $view->media());
    }

    public function test_media_casts_to_video_view(): void
    {
        $data = [
            'record' => ['uri' => 'at://external/uri'],
            'media' => [
                '$type' => 'app.bsky.embed.video#view',
                'video' => []
            ]
        ];

        $view = new View($data);

        $this->assertInstanceOf(VideoView::class, $view->media());
    }

    public function test_invalid_media_type_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'record' => ['uri' => 'at://external/uri'],
            'media' => [
                '$type' => 'invalid.type#badView',
            ]
        ];

        (new View($data))->media();
    }
}
