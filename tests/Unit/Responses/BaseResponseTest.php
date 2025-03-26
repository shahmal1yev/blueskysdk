<?php

namespace Tests\Unit\Responses;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Responses\BaseResponse;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Traits\Castable;
use PHPUnit\Framework\TestCase;

class BaseResponseTest extends TestCase
{
    private TestableResponse $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = new TestableResponse([
            'example' => 'some value',
        ]);
    }

    public function testGetMethodWithExistingAsset()
    {
        $result = $this->resource->get('example');

        $this->assertInstanceOf(ExampleObject::class, $result);
    }

    public function testGetMethodWithNonExistingAsset()
    {
        $this->expectException(BadAssetCallException::class);

        $this->resource->get('nonexistent');
    }

    public function testExistMethod()
    {
        $this->assertTrue($this->resource->exist('example'));
        $this->assertFalse($this->resource->exist('nonexistent'));
    }

    public function testMagicCall()
    {
        $result = $this->resource->example();

        $this->assertInstanceOf(ExampleObject::class, $result);
    }

    public function testResponseCanBeSerialized(): void
    {
        $expected = json_encode([
            'example' => 'some value',
        ]);

        $this->assertJsonStringEqualsJsonString($expected, (string) $this->resource);
        $this->assertJsonStringEqualsJsonString($expected, json_encode($this->resource));
    }

    public function test_casts_fieldtype_object_correctly()
    {
        $response = new class([
            'profile' => ['name' => 'Shah'],
        ]) implements ResponseContract {
            use BaseResponse;
            use Castable;

            public function __construct($value)
            {
                $this->content = $value;
            }

            protected function casts(): array
            {
                return [
                    'profile' => \Atproto\FieldTypes\FieldType::object(DummyProfile::class),
                ];
            }
        };

        $result = $response->get('profile');

        $this->assertInstanceOf(DummyProfile::class, $result);
        $this->assertEquals('Shah', $result->data()['name']);
    }

    public function test_casts_fieldtype_union_correctly()
    {
        $response = new class([
            'embed' => [
                '$type' => DummyEmbed::nsid(),
                'url' => 'https://example.com',
            ]
        ]) implements ResponseContract {
            use BaseResponse;
            use Castable;

            public function __construct($value)
            {
                $this->content = $value;
            }

            protected function casts(): array
            {
                return [
                    'embed' => \Atproto\FieldTypes\FieldType::union([
                        DummyEmbed::class
                    ]),
                ];
            }
        };

        $result = $response->get('embed');

        $this->assertInstanceOf(DummyEmbed::class, $result);
        $this->assertEquals('https://example.com', $result->data()['url']);
    }
}

class TestableResponse implements ResponseContract
{
    use BaseResponse;
    use Castable;

    protected function casts(): array
    {
        return [
            'example' => ExampleObject::class
        ];
    }
}

class ExampleObject implements ObjectContract
{
    use BaseObject;
}

class DummyProfile implements LexiconContract
{
    private array $data;
    public function __construct(array $data) { $this->data = $data; }
    public function data(): array { return $this->data; }
    public static function nsid(): string { return 'app.test.profile'; }
    public function jsonSerialize():array {return [];}
    public function __toString(): string { return json_encode($this->data); }
}

class DummyEmbed implements LexiconContract
{
    private array $data;
    public function __construct(array $data) { $this->data = $data; }
    public function data(): array { return $this->data; }
    public static function nsid(): string { return 'app.test.embed'; }
    public function jsonSerialize():array {return [];}
    public function __toString(): string { return json_encode($this->data); }
}
