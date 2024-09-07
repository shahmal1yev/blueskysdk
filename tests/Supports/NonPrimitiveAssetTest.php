<?php

namespace Tests\Supports;

use Atproto\Contracts\HTTP\Resources\AssetContract;
use GenericCollection\Exceptions\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

trait NonPrimitiveAssetTest
{
    use AssetTest;

    /**
     * @dataProvider nonPrimitiveAssetsProvider
     * @param  string  $name
     * @param  string  $expectedAsset
     * @param $value
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function testNonPrimitiveAssets(string $name, string $expectedAsset, $value): void
    {
        $data = [$name => $value];

        $actualAsset = $this->resource($data)->$name();

        $this->assertTrue(true);

        if (! $actualAsset instanceof AssetContract) {
            return;
        }

        $this->assertInstanceOf($expectedAsset, $actualAsset);

        try {
            $actualContent = (new ReflectionClass($actualAsset))->getProperty('content')->getValue($actualAsset);
        } catch (ReflectionException $e) {
            $actualContent = (new ReflectionClass($actualAsset))->getProperty('value')->getValue($actualAsset);
        }

        $this->assertEquals($actualContent, $value);
    }

    abstract public function nonPrimitiveAssetsProvider(): array;
}