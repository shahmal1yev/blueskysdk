<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Responses\Objects\BaseObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\AssetTest;

class BaseObjectTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
        setUp as assetTestSetUp;
    }

    /** @var TestableObject */
    private $asset;

    public function setUp(): void
    {
        $this->assetTestSetUp();

        list(, , $this->asset) = self::getData();
    }

    protected static function getData(): array
    {
        return array_merge(self::assetTestGetData(), [
            new TestableObject('test value')
        ]);
    }

    public function testCastReturnsInstanceOfAssetContract()
    {
        $result = $this->asset->cast();
        $this->assertInstanceOf(ObjectContract::class, $result);
        $this->assertSame($this->asset, $result);
    }

    public function testRevertReturnsValue()
    {
        $this->assertSame('test value', $this->asset->revert());
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): TestableObject
    {
        return new TestableObject($data);
    }
}

class TestableObject implements ObjectContract
{
    use BaseObject;
}
