<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\CaptionContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Collections\CaptionCollection;
use PHPUnit\Framework\TestCase;

class CaptionCollectionTest extends TestCase
{
    use EmbedCollectionTest;

    private string $target = CaptionCollection::class;
    private string $dependency = CaptionContract::class;
    private int $maxLength = 20;

    /**
     * @throws InvalidArgumentException
     */
    public function testJsonSerialize()
    {
        $items = new $this->target($this->items(2));

        $expected = json_encode(array_map(fn () => ['foo' => 'bar'], $items->toArray()));
        $actual   = json_encode($items);

        $this->assertSame($expected, $actual);
    }
}
