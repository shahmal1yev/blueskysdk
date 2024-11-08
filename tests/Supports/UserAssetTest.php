<?php

namespace Tests\Supports;

use Atproto\Responses\Objects\AssociatedObject;
use Atproto\Responses\Objects\JoinedViaStarterPackObject;
use Atproto\Responses\Objects\ViewerObject;
use GenericCollection\Interfaces\GenericCollectionInterface;

trait UserAssetTest
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    public function primitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['did', $this->faker->uuid, 'assertIsString'],
            ['handle', $this->faker->userName, 'assertIsString'],
            ['displayName', $this->faker->name, 'assertIsString'],
            ['description', $this->faker->text, 'assertIsString'],
            ['avatar', $this->faker->imageUrl(10, 10), 'assertIsString'],
            ['banner', $this->faker->imageUrl(10, 10), 'assertIsString'],
            ['followersCount', $this->faker->numberBetween(1, 100), 'assertIsInt'],
            ['followsCount', $this->faker->numberBetween(1, 100), 'assertIsInt'],
            ['postsCount', $this->faker->numberBetween(1, 100), 'assertIsInt'],
        ];
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        return [
            ['associated', AssociatedObject::class, []],
            ['joinedViaStarterPack', JoinedViaStarterPackObject::class, []],
            ['viewer', ViewerObject::class,  []],
            ['labels', GenericCollectionInterface::class, []],
        ];
    }
}
