<?php

namespace Tests\Unit\Lexicons;

use ArgumentCountError;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Lexicons\Request;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use stdClass;
use Tests\Supports\Reflection;
use TypeError;

class RequestTest extends TestCase
{
    use Reflection;

    protected Request $request;
    protected Generator $faker;

    protected function setUp(): void
    {
        $this->request = new Request();
        $this->faker = Factory::create();
    }

    public function testUrlReturnsCorrectlyAddress(): void
    {
        $url = "https://example.com";
        $path = "path/for/example/url";
        $queryParameters = ['foo' => 'bar', 'baz' => 'qux'];

        $request = $this->request->url($url)
            ->path($path)
            ->queryParameters($queryParameters);

        $actual = $request->url();
        $expected = sprintf("%s/%s?%s", $url, $path, http_build_query(
            $queryParameters,
            '',
            null,
            PHP_QUERY_RFC3986
        ));

        $this->assertSame($expected, $actual);
    }

    protected function assertHeadersEncodedCorrectly(array $expected, array $actual): void
    {
        $expected = array_map(fn ($key, $value) => "$key: $value", array_keys($expected), array_values($expected));

        $this->assertEquals($expected, $actual);
        $this->assertIsArray($actual);
    }

    protected function assertParametersEncodedCorrectly(array $expected, string $actual): void
    {
        $expected = json_encode($expected);

        $this->assertSame($expected, $actual);
        $this->assertIsString($actual);
    }

    protected function assertQueryParametersEncodedCorrectly(array $expected, string $actual): void
    {
        $expected = http_build_query($expected);

        $this->assertSame($expected, $actual);
        $this->assertIsString($actual);
    }

    protected function randomArray(int $count = null): array
    {
        $count = $count ?: $this->faker->numberBetween(1, 100);

        $keys = [];
        $values = [];

        for ($i = 0; $i < $count; $i++) {
            $keys[] = $this->faker->word;
            $values[] = $this->faker->word;
        }

        return array_combine(
            $keys,
            $values
        );
    }

    public function testGetProtocolVersionReturnsDefaultVersion(): void
    {
        $this->assertSame('1.1', $this->request->getProtocolVersion());
    }

    public function testWithProtocolVersionChangesProtocolVersion(): void
    {
        $message = $this->request->withProtocolVersion('2.0');

        $this->assertSame('2.0', $message->getProtocolVersion());
        $this->assertSame('1.1', $this->request->getProtocolVersion());
    }

    public function testWithProtocolVersionReturnsSameInstanceIfVersionNotChanged(): void
    {
        $this->assertSame('1.1', $this->request->getProtocolVersion());
        $this->assertSame($this->request, $this->request->withProtocolVersion('1.1'));
    }

    public function testGetHeadersCanGetHeaders(): void
    {
        $headers = ['Content-Type' => ['application/json']];
        $request = $this->request->withHeader('Content-Type', ['application/json']);

        $this->assertEquals($headers, $request->getHeaders());
    }

    public function testHasHeaderReturnsTrueIfHeaderExists(): void
    {
        $request = $this->request->withHeader('Content-Type', ['application/json']);

        $this->assertTrue($request->hasHeader('Content-Type'));
        $this->assertTrue($request->hasHeader('content-type'));
        $this->assertFalse($request->hasHeader('X-Custom'));
    }

    public function testGetHeaderCanGetHeaderValues(): void
    {
        $request = $this->request->withHeader('Accept', ['application/json', 'text/html']);

        $this->assertEquals(['application/json', 'text/html'], $request->getHeader('Accept'));
        $this->assertEquals([], $request->getHeader('X-Custom'));
    }

    public function testGetHeaderLineReturnsHeaderValuesSeparatedByComma(): void
    {
        $request = $this->request->withHeader('Accept', ['application/json', 'text/html']);

        $this->assertSame('application/json, text/html', $request->getHeaderLine('Accept'));
        $this->assertSame('', $request->getHeaderLine('X-Custom'));
    }

    public function testWithHeaderThrowsExceptionWhenPassedInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Header values must be RFC 7230 compatible strings');
        $this->request->withHeader('Test', new stdClass());
    }

    public function testWithAddedHeaderThrowsExceptionWhenPassedInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Header values must be RFC 7230 compatible strings');
        $this->request->withAddedHeader('Test', new stdClass());
    }

    public function testItCanAddHeaders(): void
    {
        $request = $this->request
            ->withHeader('Content-Type', ['application/json'])
            ->withAddedHeader('Content-Type', ['text/html']);

        $this->assertEquals(['application/json', 'text/html'], $request->getHeader('Content-Type'));
    }

    public function testItCanRemoveHeaders(): void
    {
        $request = $this->request
            ->withHeader('Content-Type', ['application/json'])
            ->withoutHeader('Content-Type');

        $this->assertFalse($request->hasHeader('Content-Type'));
        $this->assertEquals([], $request->getHeader('Content-Type'));
    }

    public function testItCanSetBody(): void
    {
        $streamMock = $this->createMock(StreamInterface::class);

        $request = $this->request->withBody($streamMock);

        $this->assertSame($streamMock, $request->getBody());
    }

    public function testMaintainsImmutabilityWhenSettingBody(): void
    {
        $stream1 = $this->createMock(StreamInterface::class);
        $stream2 = $this->createMock(StreamInterface::class);

        $request1 = $this->request->withBody($stream1);
        $request2 = $request1->withBody($stream2);

        $this->assertNotSame($request1, $request2);
        $this->assertSame($stream1, $request1->getBody());
        $this->assertSame($stream2, $request2->getBody());
    }

    public function testMaintainsImmutabilityWhenSettingHeaders(): void
    {
        $request1 = $this->request->withHeader('Content-Type', ['application/json']);
        $request2 = $request1->withHeader('Content-Type', ['text/html']);

        $this->assertNotSame($request1, $request2);
        $this->assertEquals(['application/json'], $request1->getHeader('Content-Type'));
        $this->assertEquals(['text/html'], $request2->getHeader('Content-Type'));
    }

    public function testGetRequestReturnsDefaultValue(): void
    {
        $this->assertSame('/', $this->request->getRequestTarget());
    }

    public function testGetRequestCanChangeRequestTarget(): void
    {
        $this->assertSame('/', $this->request->getRequestTarget());
        $this->assertNotSame($this->request, $instance = $this->request->withRequestTarget('/foo/bar'));
        $this->assertSame('/foo/bar', $instance->getRequestTarget());
    }

    public function testGetMethodReturnsDefaultMethod(): void
    {
        $this->assertSame('GET', $this->request->getMethod());
    }

    public function testGetMethodCanChangeMethod(): void
    {
        $this->assertSame('GET', $this->request->getMethod());

        $firstInstance = $this->request->withMethod('Post');
        $secondInstance = $firstInstance->withMethod('POST');

        $this->assertSame('Post', $firstInstance->getMethod());
        $this->assertSame('POST', $secondInstance->getMethod());

        $this->assertNotSame($this->request, $firstInstance);
        $this->assertNotSame($firstInstance, $secondInstance);
    }

    public function testItReturnsInstanceOfSDKRequestAfterSet(): void
    {
        $this->assertInstanceOf(Request::class, $this->request);
        $this->assertInstanceOf(Request::class, $this->request->withMethod('POST'));
        $this->assertInstanceOf(Request::class, $this->request->withHeader('Content-type', 'application/json'));
        $this->assertInstanceOf(Request::class, $this->request->withoutHeader('Content-type'));
        $this->assertInstanceOf(Request::class, $this->request->withUri($this->createMock(UriInterface::class)));
        $this->assertInstanceOf(Request::class, $this->request->withAddedHeader('Content-Type', 'application/json'));
        $this->assertInstanceOf(Request::class, $this->request->withProtocolVersion('2.0'));
        $this->assertInstanceOf(Request::class, $this->request->withRequestTarget('/foo/bar'));
    }

    public function testGetUriReturnsUriInstance(): void
    {
        $uriMock = $this->createMock(UriInterface::class);
        $req = $this->request->withUri($uriMock);

        $this->assertInstanceOf(UriInterface::class, $req->getUri());
        $this->assertSame($uriMock, $req->getUri());
        $this->assertNotInstanceOf(Request::class, $req->getUri());
    }
}
