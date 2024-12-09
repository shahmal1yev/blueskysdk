<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\MediaContract;
use Atproto\Lexicons\App\Bsky\Embed\Record;
use Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use PHPUnit\Framework\TestCase;

class RecordWithMediaTest extends TestCase
{
    private MediaContract $media;
    private RecordWithMedia $recordWithMedia;

    protected function setUp(): void
    {
        $this->record = $this->createMock(Record::class);
        $this->media = $this->createMock(MediaContract::class);
        $this->recordWithMedia = new RecordWithMedia($this->record, $this->media);
    }

    public function testRecord(): void
    {
        $this->assertSame($this->record, $this->recordWithMedia->record());
        $this->recordWithMedia->record($expected = $this->createMock(Record::class));
        $this->assertSame($expected, $this->recordWithMedia->record());
    }

    public function testMedia()
    {
        $this->assertSame($this->media, $this->recordWithMedia->media());
        $this->recordWithMedia->media($expected = $this->createMock(MediaContract::class));
        $this->assertSame($expected, $this->recordWithMedia->media());
    }

    public function testJsonSerialize()
    {
        $target = json_decode($this->recordWithMedia, true);

        $this->assertArrayHasKey('record', $target);
        $this->assertArrayHasKey('media', $target);
        $this->assertIsArray($target['media']);
    }
}
