<?php

namespace Tests\Unit\Lexicons\App\Bsky\Feed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Feed\Post;
use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use Atproto\Lexicons\App\Bsky\RichText\Mention;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use Carbon\Carbon;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post = new Post();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTextMethodWithBasicUsage()
    {
        $this->post->text('Hello, world!');
        $result = json_decode((string) $this->post, true);
        $this->assertEquals('Hello, world!', $result['text']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTextMethodWithMultipleItems()
    {
        $this->post->text('Hello', ', ', new Mention('example:did:123', 'user'), "! It's ", 5, " o'clock now.");
        $result = json_decode((string) $this->post, true);
        $this->assertEquals("Hello, @user! It's 5 o'clock now.", $result['text']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTagMethod()
    {
        $this->post->tag('example', 'test');
        $result = json_decode((string) $this->post, true);
        $this->assertEquals('#test', $result['text']);
        $this->assertCount(1, $result['facets']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testLinkMethod()
    {
        $this->post->link('https://shahmal1yev.dev', 'Example');
        $result = json_decode((string) $this->post, true);
        $this->assertEquals('Example', $result['text']);
        $this->assertCount(1, $result['facets']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testMentionMethod()
    {
        $this->post->mention('did:example:123', 'user');
        $result = json_decode((string) $this->post, true);
        $this->assertEquals('@user', $result['text']);
        $this->assertCount(1, $result['facets']);
    }

    public function testCombinationOfMethods()
    {
        $post = $this->createSamplePost();

        $result = json_decode((string) $post, true);
        $this->assertEquals('Hello @user! Check out this link #example_tag', $result['text']);
        $this->assertCount(3, $result['facets']);
    }

    public function testCoordinatesOfFacets()
    {
        $post = $this->createSamplePost();

        $result = json_decode((string) $post, true);

        $text = $result['text'];
        $facets = $result['facets'];

        $this->assertSame($this->calculateByteSlice($text, '@user'), $facets[0]['index']);
        $this->assertSame($this->calculateByteSlice($text, 'this link'), $facets[1]['index']);
        $this->assertSame($this->calculateByteSlice($text, '#example_tag'), $facets[2]['index']);
    }

    private function calculateByteSlice(string $text, string $substring): array
    {
        $byteStart = mb_strpos($text, $substring);
        $byteEnd = $byteStart + mb_strlen($substring);

        return [
            'byteStart' => $byteStart,
            'byteEnd' => $byteEnd,
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    private function createSamplePost(): Post
    {
        return (new Post())
            ->text('Hello ')
            ->mention('did:example:123', 'user')
            ->text('! Check out ')
            ->link('https://shahmal1yev.dev', 'this link')
            ->text(' ')
            ->tag('example_tag');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTextThrowsExceptionWhenLimitIsExceeded()
    {
        $this->post->text(str_repeat('a', 3000));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Text must be less than or equal to 3000 characters.');

        // This should cause the total text length to exceed the limit
        $this->post->text('a');
    }

    public function testTextThrowsExceptionWhenPassedInvalidArgument()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Argument at index \d+ is invalid/');

        $this->post->text(1, true, 'string', new Post());
    }

    public function testCreatedAtFieldIsSetByDefault()
    {
        $this->post->text('Test post');
        $result = json_decode((string) $this->post, true);

        $this->assertArrayHasKey('createdAt', $result);

        $createdAt = Carbon::parse($result['createdAt']);
        $now = Carbon::now();

        $this->assertTrue($createdAt->diffInSeconds($now) < 5, 'createdAt should be within the last 5 seconds');
    }

    public function testCreatedAtReturnsAssignedTime()
    {
        $futureTime = Carbon::now()->addHour();
        $this->post->createdAt($futureTime->toDateTimeImmutable());

        $result = json_decode((string) $this->post, true);
        $this->assertEquals($futureTime->toIso8601String(), $result['createdAt']);
    }

    public function testEmbedMethod()
    {
        $embed = $this->createMock(EmbedInterface::class);
        $embed->method('jsonSerialize')->willReturn(['embedData' => 'value']);

        $this->post->embed($embed);

        $result = json_decode((string) $this->post, true);
        $this->assertArrayHasKey('embed', $result);
        $this->assertEquals(['embedData' => 'value'], $result['embed']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testJsonSerialize()
    {
        $this->post->text('Test post: ', new Mention('reference', 'label'));
        $embed = $this->createMock(EmbedInterface::class);
        $embed->method('jsonSerialize')->willReturn(['embedKey' => 'embedValue']);
        $this->post->embed($embed);
        $sRef = new StrongRef('foo', 'bar');
        $this->post->reply($sRef, clone $sRef);

        $result = $this->post->jsonSerialize();
        $this->assertArrayHasKey('$type', $result);
        $this->assertEquals('app.bsky.feed.post', $result['$type']);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('createdAt', $result);
        $this->assertArrayHasKey('facets', $result);
        $this->assertArrayHasKey('embed', $result);
        $this->assertArrayHasKey('replyRef', $result);
    }

    public function testConstructorWorksCorrectlyOnDirectBuild()
    {
        $result = json_decode((string) $this->post, true);
        $this->assertIsArray($result);
        $this->assertEquals($result, json_decode(json_encode($result), true));
    }
}
