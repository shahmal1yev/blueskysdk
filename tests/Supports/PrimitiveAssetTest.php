<?php

namespace Tests\Supports;

use GenericCollection\Exceptions\InvalidArgumentException;

trait PrimitiveAssetTest
{
    use AssetTest;

    /**
     * @dataProvider primitiveAssetsProvider
     */
    public function testPrimitiveAssets(string $assetKey, $assetValue, string $methodName): void
    {
        $value = $assetValue;

        $this->{$methodName}(
            $this->resource([$assetKey => $value])->{$assetKey}()
        );
    }

    /**
     * @dataProvider falsyValuesProvider
     * @throws InvalidArgumentException
     */
    public function testAssetsWithFalsyValues($falsyValue)
    {
        $primitiveAssets = array_column($this->primitiveAssetsProvider(), 0);

        foreach ($primitiveAssets as $asset) {
            $resource = $this->resource([$asset => $falsyValue]);
            $this->assertSame($falsyValue, $resource->{$asset}());
        }
    }

    public function falsyValuesProvider(): array
    {
        list(, static::$falsyValues) = self::getData();

        return array_map(fn ($value) => [$value], static::$falsyValues);
    }

    abstract public function primitiveAssetsProvider(): array;
}
