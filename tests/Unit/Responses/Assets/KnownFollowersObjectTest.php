<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\FollowersObject;
use Atproto\Responses\Objects\KnownFollowersObject;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class KnownFollowersObjectTest extends TestCase
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): KnownFollowersObject
    {
        return new KnownFollowersObject($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($faker) = self::getData();

        $count = $faker->numberBetween(1, 20);

        return [
            ['followers', FollowersObject::class, [array_map(fn () => [
                'displayName' => $faker->name,
            ], range(1, $count))]],
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
