<?php

namespace Tests\Unit\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Feed\GetTimeline;
use PHPUnit\Framework\TestCase;

class GetTimelineTest extends TestCase
{
    private static GetTimeline $instance;

    public static function setUpBeforeClass(): void
    {
        static::$instance = (new Client())->app()->bsky()->feed()->getTimeline()->forge();

    }

    public function testLimitCanChangeTheLimit(): void
    {
        $getTimeline = static::$instance;

        $this->assertSame(50, $getTimeline->limit()); // default value

        $getTimeline->limit($expected = 10);
        $actual = $getTimeline->limit();

        $this->assertSame($expected, $actual);
    }

    /** @dataProvider provideInvalidLimitCases */
    public function testLimitThrowsExceptionWhenPassedInvalidArgument(int $invalidLimit): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("The limit must be between or equal to 1 and 100.");

        static::$instance->limit($invalidLimit);
    }

    public static function provideInvalidLimitCases(): array
    {
        return [
            [0],
            [-1],
            [101],
            [102]
        ];
    }

    public function testCursorCanChangeTheCursor(): void
    {
        $this->assertNull(static::$instance->cursor()); // it is not available by default

        static::$instance->cursor($expected = 'cursor value');
        $actual = static::$instance->cursor();

        $this->assertSame($expected, $actual);
    }

    public function testAlgorithmCanChangeTheAlgorithm(): void
    {
        $this->assertNull(static::$instance->algorithm());

        static::$instance->algorithm($expected = 'algorithm value');
        $actual = static::$instance->algorithm();

        $this->assertSame($expected, $actual);
    }
}
