<?php

namespace Atproto\Resources\Assets;

use Carbon\Carbon;
use Faker\Generator;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class LabelAssetTest extends TestCase
{
    use PrimitiveAssetTest, NonPrimitiveAssetTest;

    /**
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): LabelAsset
    {
        return new LabelAsset($data);
    }

    public function nonPrimitiveAssetsProvider(): array
    {
        list($faker) = self::getData();

        return [
            ['cts', Carbon::class, $faker->dateTime->format(DATE_ATOM)],
            ['exp', Carbon::class, $faker->dateTime->format(DATE_ATOM)],
        ];
    }

    public function primitiveAssetsProvider(): array
    {
        /** @var Generator $faker */
        list($faker) = self::getData();

        return [
            ['ver', $faker->numberBetween(0, 10), 'assertIsInt'],
            ['src', $faker->word, 'assertIsString'],
            ['uri', $faker->url, 'assertIsString'],
            ['cid', $faker->uuid, 'assertIsString'],
            ['val', $faker->word, 'assertIsString'],
            ['byte', $faker->word, 'assertIsString'],
            ['neg', $faker->boolean, 'assertIsBool'],
        ];
    }
}
