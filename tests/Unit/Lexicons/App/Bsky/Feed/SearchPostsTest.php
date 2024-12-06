<?php

namespace Tests\Unit\Lexicons\App\Bsky\Feed;

use Atproto\Client;
use Atproto\Enums\SearchPost\SortEnum;
use Atproto\Exceptions\BlueskyException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Feed\Post;
use Atproto\Lexicons\App\Bsky\Feed\SearchPosts;
use Carbon\Carbon;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SearchPostsTest extends TestCase
{
    private SearchPosts $searchPosts;

    public function setUp(): void
    {
        $this->searchPosts = new SearchPosts($this->createMock(Client::class), 'query');
    }

    public function testQueryReturnsCorrectResult(): void
    {
        $this->assertSame($this->searchPosts->q(), 'query');
    }

    public function testQueryCanSetNewValue(): void
    {
        $this->searchPosts->q('new query');

        $this->assertSame($this->searchPosts->q(), 'new query');
    }

    public function testSortReturnsDefaultValue(): void
    {
        $this->assertSame($this->searchPosts->sort(), 'latest');
    }

    public function testSortCanSetNewValue(): void
    {
        $this->searchPosts->sort(SortEnum::get('top'));

        $this->assertSame($this->searchPosts->sort(), 'top');
    }

    public function testSinceCanSetNewValue(): void
    {
        $date = '2020-01-01';
        $this->searchPosts->since(Carbon::parse($date)->toDateTimeImmutable());

        $actual = $this->searchPosts->since();

        $this->assertInstanceOf(DateTimeImmutable::class, $actual);
        $this->assertSame($actual->format('Y-m-d'), $date);
    }

    public function testSinceReturnsNull(): void
    {
        $this->assertNull($this->searchPosts->since());
    }

    public function testUntilCanSetNewValue(): void
    {
        $date = '2020-01-01';
        $this->searchPosts->until(Carbon::parse($date)->toDateTimeImmutable());

        $actual = $this->searchPosts->until();

        $this->assertInstanceOf(DateTimeImmutable::class, $actual);
        $this->assertSame($actual->format('Y-m-d'), $date);
    }

    public function testUntilReturnsNull(): void
    {
        $this->assertNull($this->searchPosts->until());
    }

    public function testAuthorReturnsExpectedResult(): void
    {
        $this->searchPosts->author('John Doe');
        $this->assertSame($this->searchPosts->author(), 'John Doe');
    }

    public function testAuthorReturnsNull(): void
    {
        $this->assertNull($this->searchPosts->author());
    }

    public function testLangCanSetNewValue(): void
    {
        $valid = ['az', 'az-AZ'];

        foreach($valid as $lang) {
            $this->searchPosts->lang($lang);
            $this->assertSame($this->searchPosts->lang(), $lang);
        }
    }

    public function testLangReturnsNull(): void
    {
        $this->assertNull($this->searchPosts->lang());
    }

    public function testLangThrowsExceptionWhenPassedInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid language code. Expected format: "xx" or "xx-XX".');

        $this->searchPosts->lang('xx-Xx');
    }

    public function testDomainCanSetNewValue(): void
    {
        $this->searchPosts->domain('shahmal1yev.dev');

        $this->assertSame($this->searchPosts->domain(), 'shahmal1yev.dev');
    }

    public function testDomainThrowsExceptionWhenPassedInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid domain format');

        $this->searchPosts->domain('$2122178');
    }

    public function testDomainReturnsNull(): void
    {
        $this->assertNull($this->searchPosts->domain());
    }

    public function testUrlCanSetNewValue(): void
    {
        $this->searchPosts->_url('https://shahmal1yev.dev');

        $this->assertSame($this->searchPosts->_url(), 'https://shahmal1yev.dev');
    }

    public function testUrlReturnsNull(): void
    {
        $this->assertNull($this->searchPosts->_url());
    }

    public function testUrlThrowsExceptionWhenPassedInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL format.');

        $this->searchPosts->_url('invalid');
    }

    public function testTagReturnsDefaultValueAsEmptyArray(): void
    {
        $actual = $this->searchPosts->tag();

        $this->assertEmpty($actual);
        $this->assertIsArray($actual);
    }

    public function testTagCanSetNewValue(): void
    {
        $valid = [
            'foo',
            'bar',
            'baz'
        ];

        $this->searchPosts->tag($valid);

        $this->assertSame($this->searchPosts->tag(), $valid);
    }

    public function testTagThrowsExceptionWhenPassedArgumentsContainsLimitExceededItem(): void
    {
        $limit = 640;

        $this->searchPosts->tag([str_repeat('x', $limit)]);
        $this->assertSame($this->searchPosts->tag(), [str_repeat('x', $limit)]);

        $this->expectException(BlueskyException::class);
        $this->expectExceptionMessage(sprintf(
            'Tag must be a string and can\'t be longer than 640 characters: %s',
            implode(', ', [str_repeat('x', ++$limit)])
        ));

        $this->searchPosts->tag([str_repeat('x', ++$limit)]);
    }

    public function testLimitReturnsDefaultValue(): void
    {
        $this->assertSame(25, $this->searchPosts->limit());
    }
    
    public function testLimitCanSetNewValue(): void
    {
        $this->searchPosts->limit(10);
        
        $this->assertSame(10, $this->searchPosts->limit());
    }
    
    public function testLimitThrowsExceptionWhenPassedInvalidArgument(): void
    {
        $value = [0, 101][rand(0, 1)];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Limit must be between 1 and 100: 1 <= $limit <= 100');

        $this->searchPosts->limit($value);
    }

    public function testCursorReturnsNull(): void
    {
        $this->assertNull($this->searchPosts->cursor());
    }

    public function testCursorCanSetNewValue(): void
    {
        $this->searchPosts->cursor('cursor');

        $this->assertSame($this->searchPosts->cursor(), 'cursor');
    }
}
