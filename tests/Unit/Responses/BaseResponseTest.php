<?php

namespace Tests\Unit\Responses;

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
