<?php

namespace Tests\Unit\Lexicons\App\Bsky\Feed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\VideoInterface;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Blob;
use Atproto\Lexicons\App\Bsky\Embed\Video;
use Atproto\Lexicons\App\Bsky\Feed\Post;
use Atproto\Lexicons\App\Bsky\RichText\FeatureAbstract;
use Atproto\Lexicons\App\Bsky\RichText\Mention;
use Carbon\Carbon;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    private Post $post;

    public function setUp(): void
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
        $result = json_decode($this->post, true);
        $this->assertEquals('Hello, world!', $result['text']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTextMethod()
    {
        $this->post->text('Hello', ', ', new Mention('example:did:123', 'user'), "! It's ", 5, " o'clock now.");
        $result = json_decode(json_encode($this->post), true);
        $this->assertEquals("Hello, @user! It's 5 o'clock now.", $result['text']);

        $this->post->text('This ', new Mention('example:did:123', 'user'), '!');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTagMethod()
    {
        $this->post->tag('example', 'test');
        $result = json_decode($this->post, true);
        $this->assertEquals('#test', $result['text']);
        $this->assertCount(1, $result['facets']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testLinkMethod()
    {
        $this->post->link('https://example.com', 'Example');
        $result = json_decode($this->post, true);
        $this->assertEquals('Example', $result['text']);
        $this->assertCount(1, $result['facets']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testMentionMethod()
    {
        $this->post->mention('did:example:123', 'user');
        $result = json_decode($this->post, true);
        $this->assertEquals('@user', $result['text']);
        $this->assertCount(1, $result['facets']);
    }

    public function testCombinationOfMethods()
    {
        $this->post = $this->post();

        $result = json_decode($this->post, true);
        $this->assertEquals('Hello @user! Check out this link #example_tag', $result['text']);
        $this->assertCount(3, $result['facets']);
    }

    public function testCoordinatesOfFacets(): void
    {
        $this->post = $this->post();

        $result = json_decode($this->post, true);

        $text = $result['text'];
        $facets = $result['facets'];

        $this->assertSame($facets[0]['index'], $this->bytes($text, "@user"));
        $this->assertSame($facets[1]['index'], $this->bytes($text, "this link"));
        $this->assertSame($facets[2]['index'], $this->bytes($text, "#example_tag"));
    }

    private function bytes(string $haystack, string $needle): array
    {
        $pos = mb_strpos($haystack, $needle);
        $len = $pos + mb_strlen($needle);

        return [
            'byteStart' => $pos,
            'byteEnd' => $len,
        ];
    }

    private function post(): Post
    {
        return $this->post->text('Hello ')
            ->mention('did:example:123', 'user')
            ->text('! Check out ')
            ->link('https://example.com', 'this link')
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

        $this->post->text(str_repeat('a', 3001));
    }

    public function testTextThrowsExceptionWhenPassedInvalidArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Argument at index 4 is invalid: must be a string or an instance of " . FeatureAbstract::class
        );

        $this->post->text(1, true, 'string', new Post);
    }

    public function testCreatedAtField()
    {
        $result = json_decode($this->post, true);
        $this->assertArrayHasKey('createdAt', $result);
        $this->assertNotNull($result['createdAt']);
    }

    public function testEmbed(): void
    {
        $video = $this->getMockBuilder(VideoInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $video->expects($this->once())
            ->method('jsonSerialize')
            ->willReturn(['foo' => 'bar']);

        $this->post->embed($video);

        $expected = ['foo' => 'bar'];
        $actual = json_decode($this->post, true);

        $this->assertArrayHasKey('embed', $actual);
        $this->assertSame($expected, $actual['embed']);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testJsonSerialize()
    {
        $this->post->text('Test post: ', new Mention('reference', 'label'));
        $this->post->embed($embed = $this->createMock(EmbedInterface::class));

        $result = $this->post->jsonSerialize();
        $this->assertArrayHasKey('$type', $result);
        $this->assertEquals('app.bsky.feed.post', $result['$type']);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('createdAt', $result);
        $this->assertArrayHasKey('facets', $result);
        $this->assertArrayHasKey('embed', $result);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testCreatedAtAssignedByDefault(): void
    {
        $post = $this->post->text('Test post');
        $result = json_decode($post, true);

        $this->assertSame($result['createdAt'], Carbon::now()->toIso8601String());
    }

    public function testCreatedAtReturnsAssignedTime(): void
    {
        $timestamp = time() + 3600; // Get the current timestamp
        $this->post->createdAt(new DateTimeImmutable("@$timestamp"));

        $actual = json_decode($this->post, false)->createdAt;
        $expected = Carbon::now()->modify("+1 hour")->toIso8601String();

        $this->assertSame(
            $actual,
            $expected,
        );
    }

    public function testConstructorWorksCorrectlyOnDirectBuild(): void
    {
        $array = json_decode($this->post, true);
        $json  = json_encode($array);

        $this->assertTrue(is_array($array));
        $this->assertTrue(json_encode($array) === $json);
    }
}
