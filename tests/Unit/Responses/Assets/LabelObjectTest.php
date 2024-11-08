<?php

namespace Tests\Unit\Responses\Assets;

use Atproto\Responses\Objects\LabelObject;
use Carbon\Carbon;
use Faker\Generator;
use GenericCollection\Exceptions\InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Supports\NonPrimitiveAssetTest;
use Tests\Supports\PrimitiveAssetTest;

class LabelObjectTest extends TestCase
{
    use PrimitiveAssetTest;
    use NonPrimitiveAssetTest;

    /**
     * @param  array  $data
     * @return LabelObject
     * @throws InvalidArgumentException
     */
    protected function resource(array $data): LabelObject
    {
        return new LabelObject($data);
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
