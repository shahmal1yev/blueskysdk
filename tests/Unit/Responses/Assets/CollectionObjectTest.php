<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Responses\Objects\BaseObject;
use Atproto\Responses\Objects\CollectionObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use GenericCollection\GenericCollection;
use GenericCollection\Interfaces\TypeInterface;
use PHPUnit\Framework\TestCase;
use Tests\Supports\AssetTest;

class CollectionObjectTest extends TestCase
{
    use AssetTest {
        getData as protected assetTestGetData;
    }

    private TestCollectionObject $collectionAsset;

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
            new TestCollectionObject([
                new ExampleObject(['data 1']),
                new ExampleObject(['data 2']),
            ])
        ]);
    }

    public function testConstructorSetsValues()
    {
        $this->assertInstanceOf(TestCollectionObject::class, $this->collectionAsset);
    }

    public function testCastMethod()
    {
        $result = $this->collectionAsset->cast();

        $this->assertInstanceOf(TestCollectionObject::class, $result);
    }

    public function testGetMethod()
    {
        foreach($this->collectionAsset as $item) {
            $this->assertInstanceOf(ExampleObject::class, $item);
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
    protected function resource(array $data): TestCollectionObject
    {
        return new TestCollectionObject($data);
    }
}

class TestCollectionObject extends GenericCollection implements ObjectContract
{
    use CollectionObject;

    public function __construct(array $content)
    {
        parent::__construct(new ExampleAssetType(), $content);
    }

    public function item($data): ObjectContract
    {
        return new ExampleObject($data);
    }

    public function type(): TypeInterface
    {
        return new ExampleAssetType();
    }
}

class ExampleObject implements ObjectContract
{
    use BaseObject;

    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function cast(): ExampleObject
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
        return $value instanceof ExampleObject;
    }
}
