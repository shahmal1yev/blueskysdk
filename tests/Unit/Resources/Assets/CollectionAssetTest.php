<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Contracts\Resources\AssetContract;
use Atproto\Resources\Assets\CollectionAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;
use PHPUnit\Framework\TestCase;
use Tests\Supports\AssetTest;

class CollectionAssetTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
    }

    private TestCollectionAsset $collectionAsset;

    /**
     * @throws InvalidArgumentException
     */
    protected function setUp(): void
    {
        parent::setUp();

        list($this->faker, static::$falsyValues, $this->collectionAsset) = self::getData();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected static function getData(): array
    {
        return array_merge(self::assetTestGetData(), [
            new TestCollectionAsset([
                new ExampleAsset(['data 1']),
                new ExampleAsset(['data 2']),
            ])
        ]);
    }

    public function testConstructorSetsValues()
    {
        $this->assertInstanceOf(TestCollectionAsset::class, $this->collectionAsset);
    }

    public function testCastMethod()
    {
        $result = $this->collectionAsset->cast();

        $this->assertInstanceOf(TestCollectionAsset::class, $result);
    }

    public function testGetMethod()
    {
        foreach($this->collectionAsset as $item) {
            $this->assertInstanceOf(ExampleAsset::class, $item);
        }
    }


    public function testTypeMethod()
    {
        $type = $this->collectionAsset->type();

        $this->assertInstanceOf(ExampleAssetType::class, $type);
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): TestCollectionAsset
    {
        return new TestCollectionAsset($data);
    }
}

class TestCollectionAsset extends GenericCollection implements AssetContract
{
    use CollectionAsset;

    public function __construct(array $content)
    {
        parent::__construct(new ExampleAssetType(), $content);
    }

    public function item($data): AssetContract
    {
        return new ExampleAsset($data);
    }

    public function type(): TypeInterface
    {
        return new ExampleAssetType();
    }
}

class ExampleAsset implements AssetContract
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function cast(): ExampleAsset
    {
        return $this;
    }

    public function revert()
    {
        return $this->value;
    }
}

class ExampleAssetType implements TypeInterface
{
    public function validate($value): bool
    {
        return $value instanceof ExampleAsset;
    }
}
