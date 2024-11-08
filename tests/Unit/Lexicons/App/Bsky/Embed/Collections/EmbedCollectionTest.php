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
            "%s given",
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
}
