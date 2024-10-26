<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Video;

trait EmbedCollectionTest
{
    private function items(int $limit = null): iterable
    {
        $items = [];
        $limit = $limit ?: 2;

        for ($i = 0; $i < $limit; $i++) {
            $item = $this->getMockBuilder($this->dependency)
                ->disableOriginalConstructor()
                ->getMock();

            $item->expects($this->any())
                ->method('jsonSerialize')
                ->willReturn(['foo' => 'bar']);

            $items[] = $item;
        }

        return $items;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function test__constructThatFillsCorrectlyCollection(): void
    {
        $expected = $this->items();
        $collection = new $this->target($expected);

        $this->assertSame($expected, $collection->toArray());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testValidateThrowsExceptionWhenPassedInvalidArgument(): void
    {
        $given = $this->createMock(Video::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            "must implement interface %s, instance of %s given",
            $this->dependency,
            get_class($given)
        ));

        new $this->target([$given]);
    }

    public function testValidateThrowsExceptionWhenLimitExceed(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("collection exceeds maximum size");

        new $this->target($this->items(++$this->maxLength));
    }

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