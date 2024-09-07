<?php

namespace Tests\Supports;

use Atproto\Resources\Assets\AssociatedAsset;
use Atproto\Resources\Assets\JoinedViaStarterPackAsset;
use Atproto\Resources\Assets\ViewerAsset;
use GenericCollection\Interfaces\GenericCollectionInterface;

trait UserAssetTest
{
    use PrimitiveAssetTest, NonPrimitiveAssetTest;

    public function primitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['did', $this->faker->uuid, 'assertIsString'],
            ['handle', $this->faker->userName, 'assertIsString'],
            ['displayName', $this->faker->name, 'assertIsString'],
            ['description', $this->faker->text, 'assertIsString'],
            ['avatar', $this->faker->imageUrl(10,10), 'assertIsString'],
            ['banner', $this->faker->imageUrl(10,10), 'assertIsString'],
            ['followersCount', $this->faker->numberBetween(1,100), 'assertIsInt'],
            ['followsCount', $this->faker->numberBetween(1,100), 'assertIsInt'],
            ['postsCount', $this->faker->numberBetween(1,100), 'assertIsInt'],
        ];
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        return [
            ['associated', AssociatedAsset::class, []],
            ['joinedViaStarterPack', JoinedViaStarterPackAsset::class, []],
            ['viewer', ViewerAsset::class,  []],
            ['labels', GenericCollectionInterface::class, []],
        ];
    }
}