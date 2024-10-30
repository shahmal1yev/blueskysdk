<?php

namespace Tests\Unit\Resources;

use Atproto\Contracts\Resources\AssetContract;
use Atproto\Contracts\Resources\ResourceContract;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Atproto\Resources\Assets\BaseAsset;
use Atproto\Resources\BaseResource;
use Atproto\Traits\Castable;
use PHPUnit\Framework\TestCase;

class BaseResourceTest extends TestCase
{
    private TestableResource $resource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = new TestableResource([
            'example' => 'some value',
        ]);
    }

    public function testGetMethodWithExistingAsset()
    {
        $result = $this->resource->get('example');

        $this->assertInstanceOf(ExampleAsset::class, $result);
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

        $this->assertInstanceOf(ExampleAsset::class, $result);
    }

}

class TestableResource implements ResourceContract
{
    use BaseResource;
    use Castable;

    protected function casts(): array
    {
        return [
            'example' => ExampleAsset::class
        ];
    }
}

class ExampleAsset implements AssetContract
{
    use BaseAsset;
}
