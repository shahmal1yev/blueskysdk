<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\MediaInterface;
use Atproto\Lexicons\App\Bsky\Embed\RecordWithMedia;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use PHPUnit\Framework\TestCase;

class RecordWithMediaTest extends TestCase
{
    private MediaInterface $media;
    private RecordWithMedia $recordWithMedia;

    protected function setUp(): void
    {
        $this->media = $this->createMock(MediaInterface::class);
        $this->recordWithMedia = new RecordWithMedia($this->createMock(StrongRef::class), $this->media);
    }

    public function testMedia()
    {
        $this->assertSame($this->media, $this->recordWithMedia->media());
        $this->recordWithMedia->media($expected = $this->createMock(MediaInterface::class));
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
