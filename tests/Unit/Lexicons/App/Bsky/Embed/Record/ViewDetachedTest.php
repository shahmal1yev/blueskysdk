<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\Record;

use Atproto\Lexicons\App\Bsky\Embed\Record\ViewDetached;
use PHPUnit\Framework\TestCase;

class ViewDetachedTest extends TestCase
{
    public function test_nsid_is_correct(): void
    {
        $this->assertSame(
            'app.bsky.embed.record#viewDetached',
            ViewDetached::nsid()
        );

        $this->assertSame(
            'app.bsky.embed.record#viewDetached',
            (new ViewDetached([]))->nsid()
        );
    }

    public function test_detached_casts_to_true(): void
    {
        $data = [
            '$type' => 'app.bsky.embed.record#viewDetached',
            'detached' => true
        ];

        $viewDetached = new ViewDetached($data);

        $this->assertTrue(is_bool($viewDetached->detached()));
        $this->assertTrue($viewDetached->detached());
    }
}
