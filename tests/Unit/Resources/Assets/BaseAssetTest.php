<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Resources\Assets\BaseAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Atproto\Contracts\HTTP\Resources\AssetContract;
use Tests\Supports\AssetTest;

class BaseAssetTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
        setUp as assetTestSetUp;
    }

    /** @var TestableAsset */
    private $asset;

    public function setUp(): void
    {
        $this->assetTestSetUp();

        list(, , $this->asset) = self::getData();
    }

    protected static function getData(): array
    {
        return array_merge(self::assetTestGetData(), [
            new TestableAsset('test value')
        ]);
    }

    public function testCastReturnsInstanceOfAssetContract()
    {
        $result = $this->asset->cast();
        $this->assertInstanceOf(AssetContract::class, $result);
        $this->assertSame($this->asset, $result);
    }

    public function testRevertReturnsValue()
    {
        $this->assertSame('test value', $this->asset->revert());
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): TestableAsset
    {
        return new TestableAsset($data);
    }
}

class TestableAsset implements AssetContract
{
    use BaseAsset;
}
