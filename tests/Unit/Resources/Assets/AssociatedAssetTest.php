<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Resources\Assets\AssociatedAsset;
use Atproto\Resources\Assets\ChatAsset;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\PrimitiveAssetTest;
use Tests\Supports\NonPrimitiveAssetTest;

class AssociatedAssetTest extends TestCase
{
    use PrimitiveAssetTest, NonPrimitiveAssetTest;

    public function primitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['lists', $this->faker->numberBetween(1,10), 'assertIsInt'],
            ['feedgens', $this->faker->numberBetween(1,10), 'assertIsInt'],
            ['starterPacks', $this->faker->numberBetween(1,10), 'assertIsInt'],
            ['labeler', $this->faker->boolean, 'assertIsBool'],
        ];
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['chat', ChatAsset::class, ['allowIncoming' => $this->faker->shuffleString]],
        ];
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function resource($data): AssociatedAsset
    {
        return new AssociatedAsset($data);
    }
}
