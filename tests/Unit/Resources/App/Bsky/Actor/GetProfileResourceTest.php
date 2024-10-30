<?php

namespace Tests\Unit\Resources\App\Bsky\Actor;

use Atproto\Contracts\Resources\ResourceContract;
use Atproto\Resources\App\Bsky\Actor\GetProfileResource;
use Atproto\Resources\Assets\AssociatedAsset;
use Atproto\Resources\Assets\DatetimeAsset;
use Atproto\Resources\Assets\JoinedViaStarterPackAsset;
use Atproto\Resources\Assets\ViewerAsset;
use GenericCollection\Interfaces\GenericCollectionInterface;
use PHPUnit\Framework\TestCase;
use Tests\Supports\DateAssetTest;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class GetProfileResourceTest extends TestCase
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    public function primitiveAssetsProvider(): array
    {
        return [
            ['displayName', 'string', 'assertIsString'],
            ['banner', 'string', 'assertIsString'],
            ['description', 'string', 'assertIsString'],
            ['avatar', 'string', 'assertIsString'],
            ['handle', 'string', 'assertIsString'],
            ['did', 'string', 'assertIsString'],
            ['followersCount', 1, 'assertIsInt'],
            ['postsCount', 1, 'assertIsInt'],
            ['followsCount', 1, 'assertIsInt'],
        ];
    }

    /**
     * @return array[]
     */
    public function nonPrimitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['associated', AssociatedAsset::class, ['data' => $this->faker->uuid]],
            ['joinedViaStarterPack', JoinedViaStarterPackAsset::class, ['data' => $this->faker->uuid]],
            ['viewer', ViewerAsset::class, ['data' => $this->faker->uuid]],
            ['labels', GenericCollectionInterface::class, [
                ['cts' => $this->faker->dateTime],
                ['exp' => $this->faker->dateTime],
            ]],
            ['indexedAt', DatetimeAsset::class, $this->faker->dateTime],
            ['createdAt', DatetimeAsset::class, $this->faker->dateTime],
        ];
    }

    protected function resource(array $data): ResourceContract
    {
        return new GetProfileResource($data);
    }
}
