<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\ImageInterface;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Collections\ImageCollection;
use PHPUnit\Framework\TestCase;

class ImageCollectionTest extends TestCase
{
    use EmbedCollectionTest;

    private string $target = ImageCollection::class;
    private string $dependency = ImageInterface::class;
    private int $maxLength = 4;
    private int $maxSizeOfItem = 1000000;

    public function testValidateThrowsExceptionWherePassedThatSizeGreaterThanLimit(): void
    {
        $dependency = $this->getMockBuilder($this->dependency)
            ->disableOriginalConstructor()
            ->getMock();

        $dependency->expects($this->once())
            ->method('size')
            ->willReturn(++$this->maxSizeOfItem);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("collection only accepts images with size less than");

        new ImageCollection([$dependency]);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testJsonSerialize()
    {
        $items = new $this->target($this->items(2));

        $expected = json_encode(['images' => array_map(fn () => ['foo' => 'bar'], $items->toArray()), '$type' => 'app.bsky.embed.images']);
        $actual   = json_encode($items);

        $this->assertSame($expected, $actual);
    }
}
