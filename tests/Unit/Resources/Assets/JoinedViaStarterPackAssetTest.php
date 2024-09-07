<?php

namespace Tests\Unit\Resources\Assets;

use Atproto\Resources\Assets\CreatorAsset;
use Atproto\Resources\Assets\JoinedViaStarterPackAsset;
use Atproto\Resources\Assets\LabelsAsset;
use Carbon\Carbon;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class JoinedViaStarterPackAssetTest extends TestCase
{
    use PrimitiveAssetTest, NonPrimitiveAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): JoinedViaStarterPackAsset
    {
        return new JoinedViaStarterPackAsset($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['creator', CreatorAsset::class, []],
            ['labels', LabelsAsset::class, []],
            ['indexedAt', Carbon::class, $this->faker->dateTime->format(DATE_ATOM)],
        ];
    }

    public function primitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['uri', $this->faker->word, 'assertIsString'],
            ['cid', $this->faker->word, 'assertIsString'],
            ['listItemCount', $this->faker->numberBetween(1,10), 'assertIsInt'],
            ['joinedWeekCount', $this->faker->numberBetween(1,10), 'assertIsInt'],
            ['joinedAllCount', $this->faker->numberBetween(1,10), 'assertIsInt'],
        ];
    }
}
