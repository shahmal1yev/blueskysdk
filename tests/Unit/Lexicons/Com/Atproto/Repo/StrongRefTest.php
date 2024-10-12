<?php

namespace Tests\Unit\Lexicons\Com\Atproto\Repo;

use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use PHPUnit\Framework\TestCase;

class StrongRefTest extends TestCase
{
    private StrongRef $strongRef;

    protected function setUp(): void
    {
        $this->strongRef = new StrongRef('foo', 'bar');
    }

    public function testUri()
    {
        $this->assertSame('foo', $this->strongRef->uri());
        $this->strongRef->uri('bar');
        $this->assertSame('bar', $this->strongRef->uri());
    }

    public function testCid()
    {
        $this->assertSame('bar', $this->strongRef->cid());
        $this->strongRef->cid('foo');
        $this->assertSame('foo', $this->strongRef->cid());
    }

    public function testJsonSerialize(): void
    {
        $expected = [
            'uri' => 'foo',
            'cid' => 'bar',
        ];

        $this->assertSame($expected, $this->strongRef->jsonSerialize());
    }
}
