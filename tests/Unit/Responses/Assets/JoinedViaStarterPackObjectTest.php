<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\CreatorObject;
use Atproto\Responses\Objects\JoinedViaStarterPackObject;
use Atproto\Responses\Objects\LabelsObject;
use Carbon\Carbon;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class JoinedViaStarterPackObjectTest extends TestCase
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): JoinedViaStarterPackObject
    {
        return new JoinedViaStarterPackObject($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['creator', CreatorObject::class, []],
            ['labels', LabelsObject::class, []],
            ['indexedAt', Carbon::class, $this->faker->dateTime->format(DATE_ATOM)],
        ];
    }

    public function primitiveAssetsProvider(): array
    {
        list($this->faker) = self::getData();

        return [
            ['uri', $this->faker->word, 'assertIsString'],
            ['cid', $this->faker->word, 'assertIsString'],
            ['listItemCount', $this->faker->numberBetween(1, 10), 'assertIsInt'],
            ['joinedWeekCount', $this->faker->numberBetween(1, 10), 'assertIsInt'],
            ['joinedAllCount', $this->faker->numberBetween(1, 10), 'assertIsInt'],
        ];
    }
}
