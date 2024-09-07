<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Resources\Assets\FollowersAsset;
use Atproto\Resources\Assets\KnownFollowersAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class KnownFollowersAssetTest extends TestCase
{
    use PrimitiveAssetTest, NonPrimitiveAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): KnownFollowersAsset
    {
        return new KnownFollowersAsset($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($faker) = self::getData();

        $count = $faker->numberBetween(1,20);

        return [
            ['followers', FollowersAsset::class, [array_map(fn () => [
                'displayName' => $faker->name,
            ], range(1,$count))]],
        ];
    }

    public function primitiveAssetsProvider(): array
    {
        list($faker) = self::getData();

        return [
            ['count', $faker->numberBetween(1, 20), 'assertIsInt'],
        ];
    }
}
