<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\Lexicons\App\Bsky\Embed\Record;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use PHPUnit\Framework\TestCase;

class RecordTest extends TestCase
{
    private Record $record;

    private array $expected = [

        'record' => [
            'uri' => 'foo',
            'cid' => 'bar'
        ]

    ];

    public function setUp(): void
    {
        $this->record = new Record(new StrongRef('foo', 'bar'));
    }

    public function test__toStringWorksWithJsonDecodeDirectly(): void
    {
        $this->assertSame($this->expected, json_decode($this->record, true));
    }

    public function test__toStringWorksWithJsonEncodeDirectly(): void
    {
        $this->assertSame(json_encode($this->expected), json_encode($this->record));
    }
}
