<?php

namespace Tests\Unit\Lexicons;

use ArgumentCountError;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Lexicons\Request;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
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

    /** @dataProvider methodProvider */
    public function testMethodSetsCorrectValue(string $method, string $property): void
    {
        $key = $this->faker->word;
        $expected = $this->faker->word;

        try {
            $reflectedProperty = $this->getPropertyValue($property, $this->request);

            $value = (is_array($reflectedProperty))
                ? [$key => $expected]
                : $expected;

            $this->request->$method($value);
        } catch (TypeError $e) {
            $this->request->$method($key, $expected);
        }

        $actual = $this->getPropertyValue($property, $this->request);

        if (is_array($actual)) {
            $this->assertArrayHasKey($key, $actual);
            $actual = $actual[$key];
        }

        $this->assertSame($expected, $actual);
    }

    /** @dataProvider methodProvider */
    public function testMethodReturnsCorrectValue(string $method, string $property): void
    {
        $key = $this->faker->word;
        $expected = $this->faker->word;

        $propertyValue = $this->getPropertyValue($property, $this->request);

        if (is_array($propertyValue)) {
            $expected = [$key => $expected];
        }

        $this->setPropertyValue($property, $expected, $this->request);

        try {
            $actual = $this->request->$method();
        } catch (ArgumentCountError $e) {
            $actual = $this->request->$method($key);
            $expected = current($expected);
        }

        $this->assertSame($expected, $actual);
    }

    /** @dataProvider methodProvider */
    public function testMethodReturnsSameInstanceWhenSettingValue(string $method, string $property): void
    {
        $propertyValue = $this->getPropertyValue($property, $this->request);

        $value = is_array($propertyValue)
            ? [$this->faker->word]
            : $this->faker->word;

        try {
            $actual = $this->request->$method($value);
        } catch (TypeError $e) {
            $actual = $this->request->$method($this->faker->word, $this->faker->word);
        }

        $this->assertInstanceOf(RequestContract::class, $actual);
        $this->assertInstanceOf(Request::class, $actual);
        $this->assertSame($this->request, $actual);
    }

    /** @dataProvider encodableProvider */
    public function testMethodsReturnEncodableContentCorrectly(string $property, string $method, string $verifier): void
    {
        $content = $this->randomArray();

        $this->setPropertyValue($property, $content, $this->request);

        $expected = $content;
        $actual = $this->request->$method(true);

        $this->$verifier($expected, $actual);
    }

    public function testUrlReturnsCorrectlyAddress(): void
    {
        $origin = "https://example.com";
        $path = "path/for/example/url";
        $queryParameters = ['foo' => 'bar', 'baz' => 'qux'];

        $this->request->origin($origin)
            ->path($path)
            ->queryParameters($queryParameters);

        $actual = $this->request->url();
        $expected = "$origin/$path?" . http_build_query($queryParameters);

        $this->assertSame($expected, $actual);
    }

    public function encodableProvider(): array
    {
        return [
            ['headers', 'headers', 'assertHeadersEncodedCorrectly'],
            ['parameters', 'parameters', 'assertParametersEncodedCorrectly'],
            ['queryParameters', 'queryParameters', 'assertQueryParametersEncodedCorrectly'],
        ];
    }

    public function methodProvider(): array
    {
        # [method, property]

        return [
            ['path', 'path'],
            ['origin', 'origin'],
            ['method', 'method'],
            ['header', 'headers'],
            ['headers', 'headers'],
            ['parameter', 'parameters'],
            ['parameters', 'parameters'],
            ['queryParameter', 'queryParameters'],
            ['queryParameters', 'queryParameters'],
        ];
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
        $headers = ['content-type' => ['application/json']];
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
        $this->expectExceptionMessage('$value must be an array or string');
        $this->request->withHeader('Test', new stdClass());
    }

    public function testWithAddedHeaderThrowsExceptionWhenPassedInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('$value must be an array or string');
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
}
