<?php

namespace Tests\Unit\Lexicons;

use Atproto\Contracts\Lexicons\APIRequestContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use stdClass;
use Tests\Supports\Reflection;

class RequestTest extends TestCase
{
    use Reflection;

    protected Request $request;

    protected function setUp(): void
    {
        $this->request = new Request();
    }

    public function testCreateRequestWithDefaults(): void
    {
        $this->assertSame('GET', $this->request->method());
        $this->assertSame('', $this->request->url());
        $this->assertSame('1.1', $this->request->protocol());
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
            '&',
            PHP_QUERY_RFC3986
        ));

        $this->assertSame($expected, $actual);
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

    public function testPathCanChangeThePathOfRequest(): void
    {
        $request = $this->request->path('/foo/bar');

        $this->assertSame('/', $this->request->path());
        $this->assertSame('/foo/bar', $request->path());
    }

    public function testUrlReturnsDefaultValue(): void
    {
        $this->assertSame('', $this->request->url());
    }

    public function testUrlCanChangeTheUrl(): void
    {
        $request = $this->request->url(APIRequestContract::API_BASE_URL);

        $actual = $request->url();
        $expected = APIRequestContract::API_BASE_URL . "/";

        $this->assertSame($expected, $actual);
    }

    public function testUrlCanHandleCorrectlyThePath(): void
    {
        $request = $this->request->url(APIRequestContract::API_BASE_URL . "/foo/bar/")
            ->path("/john/doe");

        $actual = $request->url();
        $expected = APIRequestContract::API_BASE_URL . "/foo/bar/john/doe";

        $this->assertSame($expected, $actual);
    }

    public function testUrlCanHandleCorrectlyTheQueryString(): void
    {
        $request = $this->request->url(APIRequestContract::API_BASE_URL . "/foo/bar/")
            ->path("/john/doe/")
            ->queryParameters([
                'foo' => 'bar',
                'php' => 'javascript',
            ]);

        $actual = $request->url();
        $expected = APIRequestContract::API_BASE_URL . "/foo/bar/john/doe/?" . http_build_query(
            ["foo" => "bar", "php" => "javascript"],
            '',
            '&',
            PHP_QUERY_RFC3986
        );

        $this->assertSame($expected, $actual);
        $this->assertNotSame($this->request, $request);
        $this->assertInstanceOf(Request::class, $request);
    }

    public function testUrlReturnsRequestComponentOfSdkOnSet(): void
    {
        $newInstance = $this->request->url(APIRequestContract::API_BASE_URL);

        $this->assertInstanceOf(RequestContract::class, $newInstance);
        $this->assertInstanceOf(RequestInterface::class, $newInstance);
        $this->assertInstanceOf(Request::class , $newInstance);
        $this->assertNotInstanceOf(\Nyholm\Psr7\Request::class, $newInstance);
    }

    public function testQueryParametersCanChangeTheQueryParametersAfterReset(): void
    {
        $request = $this->request->queryParameters([
            'foo' => 'bar',
        ])->queryParameters([
            'baz' => 'qux'
        ]);

        $this->assertSame(['baz' => 'qux'], $request->queryParameters());
    }

    public function testQueryParameterMethodCanAddQueryParamToAvailableQueryParameters(): void
    {
        $request = $this->request->queryParameters([
            'foo' => 'bar',
        ])->queryParameter("baz", "qux");

        $this->assertSame(['foo' => 'bar', 'baz' => 'qux'], $request->queryParameters());
    }

    public function testHeadersCanChangeTheHeaders(): void
    {
        $expected = [
            'Content-Type' => ['application/json', 'application/xml'],
            'Accept' => 'application/json',
        ];

        $request = $this->request->headers($expected);

        $expected['Accept'] = [$expected['Accept']];

        $this->assertNotSame($this->request, $request);
        $this->assertSame($expected, $request->headers());
    }

    public function testHeaderCanAddNewHeaderToAvailableHeaders(): void
    {
        $expected = [
            'Content-Type' => ['application/json', 'application/xml'],
        ];

        $request = $this->request->headers($expected)->header('Accept', ['application/json', 'application/xml']);

        $expected += ['Accept' => ['application/json', 'application/xml']];

        $this->assertNotSame($this->request, $request);
        $this->assertSame($expected, $request->headers());
    }

    public function testHeadersCanEmitHeaders(): void
    {
        $target = [
            'Content-Type' => ['application/json', 'application/xml'],
            'Accept' => ['application/json', 'application/xml'],
        ];

        $request = $this->request->headers($target);

        $result = [];

        foreach($target as $headerName => $headers) {
            foreach($headers as $header) {
                $result[] = "$headerName: $header";
            }
        }

        $this->assertSame($result, $request->headers(true));
    }

    public function testBodyReturnsDefault(): void
    {
        $this->assertSame([], $this->request->parameters());
    }

    public function testBodyReturnsNewInstanceOfSdkRequest(): void
    {
        $request = $this->request->parameters([
            'foo' => 'bar',
        ]);

        $this->assertNotSame($this->request, $request);
        $this->assertInstanceOf(Request::class, $request);
    }

    public function testParametersCanReturnsBodyCorrectly(): void
    {
        $parameters = [
            'foo' => 'bar',
            'baz' => 'qux',
            'person' => [
                'john' => 'doe'
            ]
        ];

        $request = $this->request->parameters($parameters);

        $this->assertSame($parameters, $request->parameters());
    }

    public function testParameterCanAddNewParameterToAvailableParameters(): void
    {
        $availableParameters = ['foo' => 'bar'];
        $newParam = ['person' => ['john' => 'doe']];

        $request = $this->request->parameters($availableParameters)
            ->parameter('person', $newParam['person']);

        $expected = $availableParameters + $newParam;

        $this->assertSame($expected, $request->parameters());
    }

    public function testParametersCanResetAndSetTheParameters(): void
    {
        $expected = [
            'person' => ['foo' => 'bar']
        ];

        $request = $this->request->parameter('foo', 'bar')
            ->parameters(['foo' => 'bar'])
            ->parameters($expected);

        $this->assertSame($expected, $request->parameters());
    }

    public function testBuilderMethods()
    {
        $request = $this->request
            ->url('https://api.example.com')
            ->path('/users')
            ->method('POST')
            ->header('Content-Type', 'application/json')
            ->parameter('name', 'John')
            ->queryParameter('filter', 'active');

        $this->assertEquals('https://api.example.com/users?filter=active', $request->url());
        $this->assertEquals('POST', $request->method());
        $this->assertEquals('application/json', $request->header('Content-Type'));
        $this->assertEquals('John', $request->parameter('name'));
        $this->assertEquals('active', $request->queryParameter('filter'));
    }

    public function testEmptyHeaderName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->request->header('', 'value');
    }

    public function testHeaderWithInvalidCharacters(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->request->header("Header\nName", 'value');
    }

    public function testMultipleQueryParametersWithSameName(): void
    {
        $request = $this->request
            ->queryParameter('filter', 'value1')
            ->queryParameter('filter', 'value2');

        $this->assertEquals('value2', $request->queryParameter('filter'));
    }

    public function testHeaderValueArrayTypes(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->request->header('Test', [['nested' => 'array']]);
    }
}
