<?php

namespace Tests\Unit\Responses\App\Bsky\Actor;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Responses\App\Bsky\Actor\GetProfileResponse;
use Atproto\Responses\Objects\AssociatedObject;
use Atproto\Responses\Objects\DatetimeObject;
use Atproto\Responses\Objects\JoinedViaStarterPackObject;
use Atproto\Responses\Objects\ViewerObject;
use GenericCollection\Interfaces\GenericCollectionInterface;
use PHPUnit\Framework\TestCase;
use Tests\Supports\DateAssetTest;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class GetProfileResponseTest extends TestCase
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
            ['associated', AssociatedObject::class, ['data' => $this->faker->uuid]],
            ['joinedViaStarterPack', JoinedViaStarterPackObject::class, ['data' => $this->faker->uuid]],
            ['viewer', ViewerObject::class, ['data' => $this->faker->uuid]],
            ['labels', GenericCollectionInterface::class, [
                ['cts' => $this->faker->dateTime],
                ['exp' => $this->faker->dateTime],
            ]],
            ['indexedAt', DatetimeObject::class, $this->faker->dateTime],
            ['createdAt', DatetimeObject::class, $this->faker->dateTime],
        ];
    }

    protected function resource(array $data): ResponseContract
    {
        return new GetProfileResponse($data);
    }
}
